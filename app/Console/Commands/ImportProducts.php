<?php

namespace App\Console\Commands;

use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ImportProducts extends Command
{
    protected $signature = 'products:import
        {path : CSV file path}
        {--limit= : Import only the first N rows}
        {--truncate : Clear product catalog tables before import}
        {--dry-run : Parse the CSV without writing to the database}
        {--inactive : Import products as hidden}';

    protected $description = 'Import crawled home battery products from CSV';

    public function handle(): int
    {
        $path = $this->argument('path');
        if (!is_file($path) || !is_readable($path)) {
            $this->error("CSV file is not readable: {$path}");
            return self::FAILURE;
        }

        if ($this->option('truncate') && !$this->option('dry-run')) {
            $this->truncateCatalog();
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            $this->error("Unable to open CSV file: {$path}");
            return self::FAILURE;
        }

        $header = fgetcsv($handle);
        if (!$header || count($header) < 24) {
            $this->error('CSV header is missing or invalid.');
            fclose($handle);
            return self::FAILURE;
        }

        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $stats = ['seen' => 0, 'imported' => 0, 'skipped' => 0, 'failed' => 0];

        while (($row = fgetcsv($handle)) !== false) {
            if ($limit && $stats['seen'] >= $limit) {
                break;
            }

            $stats['seen']++;

            try {
                $data = $this->normalizeRow($row);
                if (!$data || empty($data['product']['title'])) {
                    $stats['skipped']++;
                    continue;
                }

                if (!$this->option('dry-run')) {
                    DB::transaction(function () use ($data) {
                        $this->persistProduct($data);
                    });
                }

                $stats['imported']++;
            } catch (\Throwable $e) {
                $stats['failed']++;
                $this->warn("Row {$stats['seen']} failed: {$e->getMessage()}");
            }
        }

        fclose($handle);

        $this->info("Products import finished. Seen: {$stats['seen']}, imported: {$stats['imported']}, skipped: {$stats['skipped']}, failed: {$stats['failed']}");

        return $stats['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function truncateCatalog(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('product_media')->truncate();
        DB::table('product_variants')->truncate();
        DB::table('product_details')->truncate();
        DB::table('products')->truncate();
        DB::table('product_categories')->truncate();
        Schema::enableForeignKeyConstraints();
    }

    private function normalizeRow(array $row): ?array
    {
        if (count($row) < 24) {
            return null;
        }

        $suffixStart = max(24, count($row) - 6);
        $mediaIndex = $this->findFieldIndex($row, 24, $suffixStart, fn ($value) => Str::startsWith(trim($value), '{"images"'));
        $variantsIndex = $this->findFieldIndex($row, ($mediaIndex ?? 24) + 1, $suffixStart, fn ($value) => Str::startsWith(trim($value), '{"count"'));

        $descriptionRaw = $mediaIndex ? implode(',', array_slice($row, 24, $mediaIndex - 24)) : ($row[24] ?? '');
        $descriptionPayload = $this->decodeJson($descriptionRaw);
        $descriptionHtml = $this->extractDescriptionHtml($descriptionRaw, $descriptionPayload);
        $descriptionText = $this->extractDescriptionText($descriptionRaw, $descriptionPayload);

        $mediaPayload = $this->decodeJson($mediaIndex !== null ? $row[$mediaIndex] : null) ?: [];
        $variantsPayload = $this->decodeJson($variantsIndex !== null ? $row[$variantsIndex] : null) ?: [];
        $specificationsPayload = $this->decodeJson($variantsIndex !== null ? ($row[$variantsIndex + 1] ?? null) : null) ?: [];
        $seoPayload = $this->decodeJson($variantsIndex !== null ? ($row[$variantsIndex + 2] ?? null) : null) ?: [];
        $sourcePayload = $this->decodeJson($variantsIndex !== null ? ($row[$variantsIndex + 3] ?? null) : null) ?: [];

        $rawPayloadStart = $variantsIndex !== null ? $variantsIndex + 4 : ($mediaIndex !== null ? $mediaIndex + 1 : 24);
        $rawPayload = implode(',', array_slice($row, $rawPayloadStart, max(0, $suffixStart - $rawPayloadStart)));

        $selectedVariantId = trim($row[9] ?? '') ?: $this->variantIdFromUrl($row[3] ?? $row[2] ?? '');
        $tags = $this->decodeJson($row[23] ?? null);
        if (!is_array($tags)) {
            $tags = $this->splitList($row[23] ?? '');
        }

        $mainImage = $this->normalizeUrl($mediaPayload['main_image'] ?? $mediaPayload['featured_image'] ?? null);
        $featuredImage = $this->normalizeUrl($mediaPayload['featured_image'] ?? null);
        if (!$mainImage && !empty($mediaPayload['images'][0]['url'])) {
            $mainImage = $this->normalizeUrl($mediaPayload['images'][0]['url']);
        }

        $categoryName = trim($row[21] ?? '') ?: trim($row[22] ?? '') ?: 'Thuisbatterijen';

        return [
            'category_name' => $categoryName,
            'product' => [
                'crawl_id' => $this->nullableInt($row[0] ?? null),
                'site_url' => $row[1] ?? null,
                'input_url' => $row[2] ?? null,
                'final_url' => $row[3] ?? null,
                'source_site' => $row[4] ?? null,
                'platform' => $row[5] ?? null,
                'external_product_id' => $row[6] ?? null,
                'handle' => $row[7] ?? null,
                'url_key' => $row[8] ?? null,
                'selected_variant_id' => $selectedVariantId,
                'vendor' => $row[10] ?? null,
                'brand' => $row[11] ?? null,
                'title' => $row[12] ?? null,
                'currency' => $row[13] ?: 'EUR',
                'price' => $this->decimal($row[14] ?? null),
                'compare_at_price' => $this->decimal($row[15] ?? null),
                'min_variant_price' => $this->decimal($row[16] ?? null),
                'max_variant_price' => $this->decimal($row[17] ?? null),
                'availability_status' => $row[18] ?? null,
                'selected_variant_available' => $this->bool($row[19] ?? null),
                'any_variant_available' => $this->bool($row[20] ?? null),
                'product_type' => $row[21] ?? null,
                'source_category' => $row[22] ?? null,
                'tags' => $tags,
                'main_image' => $mainImage,
                'featured_image' => $featuredImage,
                'summary' => Str::limit($descriptionText, 260, ''),
                'description_text' => $descriptionText,
                'description_html' => $descriptionHtml,
                'seo_title' => $seoPayload['title'] ?? null,
                'seo_description' => $seoPayload['description'] ?? null,
                'seo_keywords' => isset($seoPayload['keywords']) ? implode(', ', (array) $seoPayload['keywords']) : null,
                'html_sha256' => $row[$suffixStart] ?? null,
                'product_hash' => $row[$suffixStart + 1] ?? null,
                'crawled_at' => $this->date($row[$suffixStart + 2] ?? null),
                'is_active' => !$this->option('inactive'),
            ],
            'description_payload' => is_array($descriptionPayload) ? $descriptionPayload : null,
            'media_payload' => $mediaPayload,
            'variants_payload' => $variantsPayload,
            'specifications_payload' => $specificationsPayload,
            'seo_payload' => $seoPayload,
            'source_payload' => $sourcePayload,
            'raw_payload' => $rawPayload,
        ];
    }

    private function persistProduct(array $data): void
    {
        $category = $this->firstOrCreateCategory($data['category_name']);
        $productData = $data['product'];
        $productData['product_category_id'] = $category->id;

        $product = Product::query()
            ->where('source_site', $productData['source_site'])
            ->where('external_product_id', $productData['external_product_id'])
            ->where('selected_variant_id', $productData['selected_variant_id'])
            ->first();

        if (!$product && !empty($productData['product_hash'])) {
            $product = Product::where('product_hash', $productData['product_hash'])->first();
        }

        if (!$product) {
            $product = new Product();
            $productData['slug'] = $this->uniqueProductSlug($productData['title'] ?: $productData['handle']);
        }

        $product->fill($productData);
        $product->save();

        ProductDetail::updateOrCreate(
            ['product_id' => $product->id],
            [
                'description_payload' => $data['description_payload'],
                'specifications' => $data['specifications_payload'],
                'seo_payload' => $data['seo_payload'],
                'source_payload' => $data['source_payload'],
                'raw_payload' => $data['raw_payload'],
                'product_payload' => null,
            ]
        );

        $this->syncMedia($product, $data['media_payload']);
        $this->syncVariants($product, $data['variants_payload'], $productData['currency'] ?? 'EUR');
    }

    private function firstOrCreateCategory(string $name): ProductCategory
    {
        $name = trim($name) ?: 'Thuisbatterijen';
        $slug = Str::slug($name) ?: Str::slug(Str::ascii($name)) ?: 'producten';

        return ProductCategory::firstOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'seo_title' => "{$name} vergelijken",
                'seo_description' => "Vergelijk {$name} voor thuisbatterij en zonne-energie opslag.",
                'seo_keywords' => "{$name}, thuisbatterij, energieopslag",
                'is_active' => true,
            ]
        );
    }

    private function syncMedia(Product $product, array $mediaPayload): void
    {
        $product->media()->delete();

        $position = 0;
        foreach (($mediaPayload['images'] ?? []) as $image) {
            $url = $this->normalizeUrl($image['url'] ?? null);
            if (!$url) {
                continue;
            }

            $product->media()->create([
                'type' => 'image',
                'url' => $url,
                'alt' => $image['alt'] ?? $product->title,
                'source' => $image['source'] ?? null,
                'sort_order' => $position++,
            ]);
        }

        foreach (($mediaPayload['videos'] ?? []) as $video) {
            $url = $this->normalizeUrl($video['url'] ?? $video['src'] ?? null);
            if (!$url) {
                continue;
            }

            $product->media()->create([
                'type' => 'video',
                'url' => $url,
                'alt' => $video['alt'] ?? $product->title,
                'source' => $video['source'] ?? null,
                'sort_order' => $position++,
            ]);
        }
    }

    private function syncVariants(Product $product, array $variantsPayload, string $currency): void
    {
        $product->variants()->delete();

        foreach (($variantsPayload['items'] ?? []) as $position => $variant) {
            if (!is_array($variant)) {
                continue;
            }

            $product->variants()->create([
                'external_variant_id' => $variant['id'] ?? null,
                'sku' => $variant['sku'] ?? ($variant['raw']['sku'] ?? null),
                'title' => $variant['title'] ?? ($variant['raw']['title'] ?? null),
                'option1' => $variant['option1'] ?? ($variant['raw']['option1'] ?? null),
                'option2' => $variant['option2'] ?? ($variant['raw']['option2'] ?? null),
                'option3' => $variant['option3'] ?? ($variant['raw']['option3'] ?? null),
                'price' => $this->decimal($variant['price'] ?? null),
                'compare_at_price' => $this->decimal($variant['compare_at_price'] ?? null),
                'currency' => $variant['currency'] ?? $currency,
                'available' => $this->bool($variant['available'] ?? false),
                'featured_image' => $variant['featured_image'] ?? null,
                'raw_payload' => $variant['raw'] ?? null,
                'sort_order' => $position,
            ]);
        }
    }

    private function uniqueProductSlug(?string $value): string
    {
        $base = Str::slug((string) $value) ?: 'product-' . Str::random(8);
        $slug = $base;
        $i = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function findFieldIndex(array $row, int $start, int $end, callable $matcher): ?int
    {
        for ($i = $start; $i < $end; $i++) {
            if (isset($row[$i]) && $matcher((string) $row[$i])) {
                return $i;
            }
        }

        return null;
    }

    private function decodeJson(?string $value): mixed
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        $decoded = json_decode(str_replace('""', '"', $value), true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

    private function extractDescriptionHtml(string $raw, mixed $payload): ?string
    {
        if (is_array($payload)) {
            return !empty($payload['html']) ? $payload['html'] : null;
        }

        if (preg_match('/"html"+\s*:\s*"+(.*?)(?:"+\s*,\s*"text|text""\s*:)/s', $raw, $match)) {
            return stripslashes(str_replace('""', '"', $match[1]));
        }

        return $raw ?: null;
    }

    private function extractDescriptionText(string $raw, mixed $payload): ?string
    {
        if (is_array($payload)) {
            if (!empty($payload['text'])) {
                return $this->cleanText($payload['text']);
            }

            if (!empty($payload['html'])) {
                return $this->cleanText($payload['html']);
            }

            return null;
        }

        if (preg_match('/text""\s*:\s*""(.*?)(?:""\s*,\s*short|""\s*,\s*content_blocks|""\s*})/s', $raw, $match)) {
            return $this->cleanText(str_replace('""', '"', $match[1]));
        }

        return $this->cleanText($raw);
    }

    private function cleanText(?string $value): ?string
    {
        $value = html_entity_decode(strip_tags(stripslashes((string) $value)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = preg_replace('/\s+/u', ' ', $value);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        return $value !== '' ? $value : null;
    }

    private function splitList(string $value): array
    {
        $value = trim($value, "[] \t\n\r\0\x0B");
        if ($value === '') {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($item) => trim($item, " \t\n\r\0\x0B\"'"),
            explode(',', $value)
        )));
    }

    private function normalizeUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        return Str::startsWith($url, '//') ? "https:{$url}" : $url;
    }

    private function variantIdFromUrl(?string $url): ?string
    {
        $query = parse_url((string) $url, PHP_URL_QUERY);
        if (!$query) {
            return null;
        }

        parse_str($query, $params);
        return isset($params['variant']) ? (string) $params['variant'] : null;
    }

    private function decimal(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = str_replace(',', '.', preg_replace('/[^\d,.\-]/', '', (string) $value));
        return is_numeric($value) ? (float) $value : null;
    }

    private function bool(mixed $value): bool
    {
        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'y'], true);
    }

    private function nullableInt(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }

    private function date(?string $value): ?Carbon
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        foreach (['d/m/Y H:i:s', 'Y-m-d H:i:s', Carbon::ATOM] as $format) {
            try {
                return Carbon::createFromFormat($format, $value);
            } catch (\Throwable) {
                // Try the next known crawler date format.
            }
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
