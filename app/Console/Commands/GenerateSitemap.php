<?php

namespace App\Console\Commands;

use App\Models\Article\Article;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Article\ArticleCategory;
use App\Models\Lunwen\LunwenTask;
class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file';

    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('daily'));

        
        //detector sitemap
        // $this->genDetectorSitemap($sitemap);
        //reducer sitemap
        // $this->genReducerSitemap($sitemap);
      

        // 生成分类sitemap
        $this->genCategorySitemap($sitemap);

        // 生成文章sitemap
        $this->genArticleSitemap($sitemap);

        // 保存到 public/sitemap.xml
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap generated successfully!');
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
            $url = Url::create(route('article.category2', [$category->name]))
                ->setLastModificationDate($category->updated_at)
                ->setChangeFrequency('weekly')
                ->setPriority(1.0);
    
            $sitemap->add($url);
        }
    }
    private function genArticleSitemap($sitemap)
    {
        $sitemap->add(Url::create('/blog')->setPriority(1)->setChangeFrequency('weekly'));
        $articles = Article::all();
        $i = 0;
        Article::chunk(40000, function ($articles) use ($sitemap,  &$i) {
            foreach ($articles as $article) {
                $url = Url::create(route('article.detail.show', [$article->category->name, $article->link]))
                ->setLastModificationDate($article->updated_at)
                    ->setChangeFrequency('weekly')
                    ->setPriority(0.8);
                $sitemap->add($url);
            }
            $i++;
        });
    }
}
