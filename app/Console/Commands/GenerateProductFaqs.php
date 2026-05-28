<?php

namespace App\Console\Commands;

use App\Models\Product\Product;
use App\Models\Product\ProductFaq;
use App\Service\AIService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateProductFaqs extends Command
{
    protected $signature = 'products:generate-faqs
        {--limit=20 : Max products to process per run}
        {--product_id= : Process only one product id}
        {--force : Regenerate even if product already has 3-5 FAQs}
        {--model=gpt-5 : AI model used for FAQ generation}';

    protected $description = 'Generate product-specific FAQs (3-5) with AI and save for product detail pages.';

    public function handle(AIService $aiService): int
    {
        $limit = max(1, min(100, (int) $this->option('limit')));
        $productId = $this->option('product_id');
        $force = (bool) $this->option('force');
        $model = trim((string) $this->option('model')) ?: AIService::MODEL_GPT_5;

        $query = Product::query()
            ->with(['category', 'detail'])
            ->withCount(['faqs as active_faqs_count' => fn ($q) => $q->where('is_active', true)])
            ->active();

        if ($productId !== null && $productId !== '') {
            $query->where('id', (int) $productId);
        }

        if (!$force) {
            $query->having('active_faqs_count', '<', 3);
        }

        $products = $query
            ->orderBy('active_faqs_count')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($products->isEmpty()) {
            $this->info('No products need FAQ generation.');
            return self::SUCCESS;
        }

        $this->info(sprintf('Processing %d product(s) for FAQ generation...', $products->count()));

        $ok = 0;
        $failed = 0;

        foreach ($products as $product) {
            try {
                $this->line(sprintf(' - Product #%d %s', $product->id, $product->title));

                [$minRequired, $maxAllowed] = $this->countBoundsByManualFaqs($product);
                if ($maxAllowed <= 0) {
                    $this->comment('   skip: manual FAQs already reach upper limit (5).');
                    continue;
                }

                $targetCount = min($maxAllowed, max($minRequired, 3));
                $raw = $aiService->chat($this->buildPrompt($product, $minRequired, $targetCount), $model);
                $decoded = $this->decodeFaqPayload((string) $raw);

                $manualHashes = ProductFaq::query()
                    ->where('product_id', $product->id)
                    ->where('is_active', true)
                    ->where('source', 'manual')
                    ->pluck('question_hash')
                    ->filter()
                    ->all();

                $faqs = $this->normalizeFaqs($decoded, $product, $minRequired, $targetCount, $manualHashes);

                if (count($faqs) < $minRequired) {
                    throw new \RuntimeException(sprintf('AI returned too few valid FAQs (%d).', count($faqs)));
                }

                DB::transaction(function () use ($product, $faqs, $model) {
                    ProductFaq::query()
                        ->where('product_id', $product->id)
                        ->where('source', 'ai')
                        ->delete();

                    foreach ($faqs as $index => $item) {
                        ProductFaq::create([
                            'product_id' => $product->id,
                            'locale' => 'nl',
                            'question' => $item['question'],
                            'answer' => $item['answer'],
                            'question_hash' => $item['hash'],
                            'sort_order' => $index,
                            'source' => 'ai',
                            'model' => $model,
                            'is_active' => true,
                            'generated_at' => now(),
                        ]);
                    }
                });

                $ok++;
                $this->info(sprintf('   done: saved %d FAQ(s).', count($faqs)));
            } catch (\Throwable $e) {
                $failed++;
                Log::error('GenerateProductFaqs failed', [
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'error' => $e->getMessage(),
                ]);

                $this->error(sprintf('   failed: %s', $e->getMessage()));
            }
        }

        $this->newLine();
        $this->info(sprintf('Finished. success=%d failed=%d', $ok, $failed));

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function countBoundsByManualFaqs(Product $product): array
    {
        $manualCount = ProductFaq::query()
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->where('source', 'manual')
            ->count();

        $minRequired = max(0, 3 - $manualCount);
        $maxAllowed = max(0, 5 - $manualCount);

        return [$minRequired, $maxAllowed];
    }

    private function buildPrompt(Product $product, int $minCount, int $maxCount): string
    {
        $title = trim((string) $product->title);
        $brand = trim((string) $product->brand);
        $category = trim((string) optional($product->category)->name);
        $type = trim((string) $product->product_type);
        $summary = Str::limit(trim((string) $product->summary), 360, '');
        $description = Str::limit(trim((string) $product->description_text), 1200, '');
        $spec = json_encode((array) optional($product->detail)->specifications, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return "You are an ecommerce product expert.\n"
            . "Write {$minCount}-{$maxCount} FAQ entries in Dutch for a single product.\n"
            . "Rules:\n"
            . "1) Output pure JSON only, no markdown.\n"
            . "2) JSON format: {\"faqs\":[{\"question\":\"...\",\"answer\":\"...\"}]}\n"
            . "3) Questions must be specific to the product context, practical, non-repetitive.\n"
            . "4) Answers must be concise (max 220 characters), factual, and user-friendly.\n"
            . "5) Avoid fabricated numbers if unknown; use cautious wording.\n\n"
            . "Product context:\n"
            . "- title: {$title}\n"
            . "- brand: {$brand}\n"
            . "- category: {$category}\n"
            . "- type: {$type}\n"
            . "- summary: {$summary}\n"
            . "- description: {$description}\n"
            . "- specs_json: {$spec}\n";
    }

    private function decodeFaqPayload(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }

        $decodedRaw = json_decode($raw, true);
        if (is_array($decodedRaw) && ($decodedRaw['errno'] ?? null) === 0 && !empty($decodedRaw['re'])) {
            $raw = (string) $decodedRaw['re'];
        }

        $cleaned = preg_replace('/^```(?:json)?\s*/i', '', $raw);
        $cleaned = preg_replace('/\s*```\s*$/', '', (string) $cleaned);
        $cleaned = trim((string) $cleaned);

        $decoded = json_decode($cleaned, true);
        if (is_array($decoded)) {
            return $this->extractFaqArray($decoded);
        }

        if (preg_match('/\{[\s\S]*\}/', $cleaned, $m)) {
            $obj = json_decode($m[0], true);
            if (is_array($obj)) {
                return $this->extractFaqArray($obj);
            }
        }

        if (preg_match('/\[[\s\S]*\]/', $cleaned, $m)) {
            $arr = json_decode($m[0], true);
            if (is_array($arr)) {
                return $arr;
            }
        }

        return [];
    }

    private function extractFaqArray(array $payload): array
    {
        if (array_is_list($payload)) {
            return $payload;
        }

        $faqs = $payload['faqs'] ?? $payload['items'] ?? [];
        return is_array($faqs) ? $faqs : [];
    }

    private function normalizeFaqs(array $items, Product $product, int $minCount, int $maxCount, array $existingHashes = []): array
    {
        $normalized = [];
        $seen = array_fill_keys($existingHashes, true);

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $question = $this->cleanText((string) ($item['question'] ?? $item['q'] ?? ''));
            $answer = $this->cleanText((string) ($item['answer'] ?? $item['a'] ?? ''));

            if ($question === '' || $answer === '') {
                continue;
            }

            if (mb_strlen($question) < 8 || mb_strlen($answer) < 12) {
                continue;
            }

            $hash = $this->questionHash($question);
            if (isset($seen[$hash])) {
                continue;
            }
            $seen[$hash] = true;

            $normalized[] = [
                'question' => Str::limit($question, 500, ''),
                'answer' => Str::limit($answer, 2000, ''),
                'hash' => $hash,
            ];

            if (count($normalized) >= $maxCount) {
                break;
            }
        }

        if (count($normalized) < $minCount) {
            foreach ($this->fallbackFaqs($product) as $item) {
                $hash = $this->questionHash($item['question']);
                if (isset($seen[$hash])) {
                    continue;
                }
                $seen[$hash] = true;
                $normalized[] = [
                    'question' => $item['question'],
                    'answer' => $item['answer'],
                    'hash' => $hash,
                ];

                if (count($normalized) >= $minCount) {
                    break;
                }
            }
        }

        return array_slice($normalized, 0, $maxCount);
    }

    private function fallbackFaqs(Product $product): array
    {
        $name = $product->title ?: 'dit product';

        return [
            [
                'question' => "Voor welk gebruik is {$name} het meest geschikt?",
                'answer' => 'Dit model is bedoeld voor dagelijks energiegebruik thuis of onderweg, afhankelijk van de capaciteit en aansluitingen.',
            ],
            [
                'question' => "Werkt {$name} ook met zonnepanelen?",
                'answer' => 'Dat hangt af van de ingangspecificaties. Controleer het ondersteunde voltage/vermogen in de productgegevens voor een veilige koppeling.',
            ],
            [
                'question' => "Hoe lang gaat {$name} mee op een volle lading?",
                'answer' => 'De gebruiksduur verschilt per apparaat en belasting. Hogere belasting betekent meestal een kortere gebruiksduur.',
            ],
            [
                'question' => "Is {$name} eenvoudig te installeren of direct te gebruiken?",
                'answer' => 'Veel modellen zijn plug-and-play. Bekijk altijd de handleiding en veiligheidsinstructies voor de eerste ingebruikname.',
            ],
            [
                'question' => "Waar moet ik op letten bij onderhoud van {$name}?",
                'answer' => 'Bewaar het product droog en geventileerd, houd laadniveau binnen aanbevolen bereik en werk firmware bij indien beschikbaar.',
            ],
        ];
    }

    private function cleanText(string $text): string
    {
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text);

        return trim((string) $text);
    }

    private function questionHash(string $question): string
    {
        return hash('sha256', Str::lower($this->cleanText($question)));
    }
}
