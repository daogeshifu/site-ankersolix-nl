<?php

namespace App\Console\Commands;

use App\Models\Article\Article;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Sitemap as SitemapTag;
use Spatie\Sitemap\Tags\Url;
use App\Models\Article\ArticleCategory;
use App\Models\Lunwen\LunwenTask;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file';

    private const PRODUCTS_SITEMAP = 'sitemap-products.xml';
    private const COLLECTIONS_SITEMAP = 'sitemap-collections.xml';
    private const PAGES_SITEMAP = 'sitemap-pages.xml';

    public function handle()
    {
        $this->generateProductsSitemap();
        $this->generateCollectionsSitemap();
        $this->generatePagesSitemap();

        SitemapIndex::create()
            ->add(SitemapTag::create(url(self::PRODUCTS_SITEMAP)))
            ->add(SitemapTag::create(url(self::COLLECTIONS_SITEMAP)))
            ->add(SitemapTag::create(url(self::PAGES_SITEMAP)))
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap index generated successfully.');
    }

    private function generateProductsSitemap(): void
    {
        $sitemap = Sitemap::create()
            ->add($this->url(route('products.index'), 'daily', 1.0));

        Product::active()
            ->whereNotNull('slug')
            ->orderBy('id')
            ->chunk(1000, function ($products) use ($sitemap) {
                foreach ($products as $product) {
                    $sitemap->add(
                        $this->url(route('products.show', $product->slug), 'weekly', 0.8, $product->updated_at)
                    );
                }
            });

        $sitemap->writeToFile(public_path(self::PRODUCTS_SITEMAP));
    }

    private function generateCollectionsSitemap(): void
    {
        $sitemap = Sitemap::create();

        ProductCategory::where('is_active', true)
            ->whereNotNull('slug')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->chunk(1000, function ($categories) use ($sitemap) {
                foreach ($categories as $category) {
                    $sitemap->add(
                        $this->url(route('products.category', $category->slug), 'weekly', 0.9, $category->updated_at)
                    );
                }
            });

        $sitemap->writeToFile(public_path(self::COLLECTIONS_SITEMAP));
    }

    private function generatePagesSitemap(): void
    {
        $sitemap = Sitemap::create()
            ->add($this->url(route('index'), 'daily', 1.0))
            ->add($this->url(route('pricing'), 'monthly', 0.8))
            ->add($this->url(route('price'), 'monthly', 0.8))
            ->add($this->url(route('terms'), 'yearly', 0.5))
            ->add($this->url(route('policy'), 'yearly', 0.5))
            ->add($this->url(route('contact'), 'monthly', 0.6))
            ->add($this->url(route('about'), 'monthly', 0.6))
            ->add($this->url(route('help'), 'monthly', 0.6))
            ->add($this->url(route('calculator'), 'weekly', 0.9))
            ->add($this->url(route('articles'), 'weekly', 0.9))
            ->add($this->url(route('news'), 'weekly', 0.9))
            ->add($this->url(route('guides'), 'weekly', 0.9))
            ->add($this->url(route('cases'), 'weekly', 0.9));

        foreach ([
            'buying-guide',
            'installation',
            'subsidy',
            'energy-saving',
            'reviews',
            'beste-thuisbatterij-2026',
            'thuisbatterij-zonder-zonnepanelen',
            'dynamische-energietarieven',
            'thuisbatterij-subsidie',
            'back-upstroom-noodstroom',
            'zonne-energie-opslaan',
            'thuisbatterij-capaciteit-uitbreiding',
            'warmtepomp-elektrische-auto',
            'thuisbatterij-zelf-installeren',
        ] as $routeName) {
            $sitemap->add($this->url(route($routeName), 'weekly', 0.9));
        }

        $this->genCategorySitemap($sitemap);
        $this->genArticleSitemap($sitemap);

        $sitemap->writeToFile(public_path(self::PAGES_SITEMAP));
    }

    private function genDetectorSitemap($sitemap)
    {
        $sitemap->add(Url::create('/detector')->setPriority(0.8)->setChangeFrequency('weekly'));

        $detector = LunwenTask::where('status', 1)->get();
        foreach ($detector as $detector) {
            $sitemap->add(route('aigc.detector.show',[$detector->order_number]))
                ->setLastModificationDate($detector->updated_at)
                ->setChangeFrequency('weekly')
                ->setPriority(0.8);
        }
    }

    private function genReducerSitemap($sitemap)
    {
        $sitemap->add(Url::create('/reducer')->setPriority(1)->setChangeFrequency('weekly'));

        $reducer = LunwenTask::where('status', 1)->get();
        foreach ($reducer as $reducer) {
            $sitemap->add(route('aigc.reducer.show',[$reducer->order_number]))
                ->setLastModificationDate($reducer->updated_at)
                ->setChangeFrequency('weekly')
                ->setPriority(0.8);
        }
    }

    private function genCategorySitemap($sitemap)
    {
        $categories = ArticleCategory::all();
        foreach ($categories as $category) {
            $sitemap->add(
                $this->url(route('article.category2', [$category->name]), 'weekly', 0.9, $category->updated_at)
            );
        }
    }

    private function genArticleSitemap($sitemap)
    {
        Article::with('category')
            ->whereNotNull('link')
            ->whereHas('category')
            ->orderBy('id')
            ->chunk(1000, function ($articles) use ($sitemap) {
                foreach ($articles as $article) {
                    $sitemap->add(
                        $this->url(
                            route('article.detail.show', [$article->category->name, $article->link]),
                            'weekly',
                            0.8,
                            $article->updated_at
                        )
                    );
                }
            });
    }

    private function url(string $url, string $changeFrequency, float $priority, $lastModificationDate = null): Url
    {
        $tag = Url::create($url)
            ->setChangeFrequency($changeFrequency)
            ->setPriority($priority);

        if ($lastModificationDate) {
            $tag->setLastModificationDate($lastModificationDate);
        }

        return $tag;
    }
}
