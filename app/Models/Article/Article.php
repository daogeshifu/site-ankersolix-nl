<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\User\User;

class Article extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    // 表名
    protected $table = 'articles';

    // Define which attributes need to be translated
    public $translatedAttributes = [
        'title', 
        'content', 
        'summary', 
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    // Keep the original fillable fields that aren't translatable
    // title 和 content 在主表中冗余存储英文版本
    protected $fillable = [
        'user_id',
        'category_id',
        'link',
        'cover',
        'title',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 文章分类
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    /**
     * 创建多语言文章
     *
     * @param array $data 文章数据
     * @param string $lang 语言代码 (en, zh, fr)
     * @return self
     * @throws \Exception
     */
    public static function createWithTranslation(array $data, string $lang): self
    {
        // 创建文章主记录（只包含非翻译字段）
        $article = new self();
        $article->user_id = $data['user_id'] ?? User::first()->id;
        $article->category_id = $data['category_id'] ?? ArticleCategory::first()->id;
        $article->link = $data['link'];
        $article->cover = $data['cover'] ?? null;
        $article->title = $data['title'];
        $article->content = $data['content'];
        $article->save();

        // 保存当前语言的翻译内容
        $translation = $article->translateOrNew($lang);
        $translation->title = $data['title'];
        $translation->content = $data['content'];
        $translation->summary = $data['summary'] ?? null;
        $translation->seo_title = $data['seo_title'] ?? null;
        $translation->seo_description = $data['seo_description'] ?? null;
        $translation->seo_keywords = $data['seo_keywords'] ?? null;
        $article->save();

        // 如果是英文版本，同时更新主表的 title 和 content（冗余存储）
        if ($lang === 'en') {
            $article->title = $data['title'];
            $article->content = $data['content'];
            $article->save();
        }

        return $article;
    }

    /**
     * 创建多语言文章
     *
     * @param array $data 文章主数据（title/content/summary等字段）
     * @param array $locales 要创建的语言列表，格式：
     *                       [
     *                          'en' => ['title' => '英文标题', ...],
     *                          'zh' => ['title' => '中文标题', ...],
     *                          'fr' => [] // 未提供字段时用主表默认
     *                       ]
     *                       如果是索引数组 ['en', 'zh']，则使用主表 title/content 填充
     * @return self
     * @throws \Exception
     */
    public static function createWithTranslations(array $data, array $locales = ['en', 'zh', 'fr']): self
    {
        // 保证主表 title/content 有值
        $defaultTitle = $data['title'] ?? '';
        $defaultContent = $data['content'] ?? '';

        // 创建主表记录
        $article = new self();
        $article->user_id = $data['user_id'] ?? User::first()->id;
        $article->category_id = $data['category_id'] ?? ArticleCategory::first()->id;
        $article->link = $data['link'] ?? null;
        $article->cover = $data['cover'] ?? null;
        $article->title = $defaultTitle;
        $article->content = $defaultContent;
        $article->save();

        // 创建每种语言的翻译
        foreach ($locales as $lang => $translationData) {
            // 如果是索引数组形式，只提供语言代码
            if (is_int($lang)) {
                $lang = $translationData;
                $translationData = [];
            }

            $translation = $article->translateOrNew($lang);
            $translation->title = $translationData['title'] ?? $defaultTitle;
            $translation->content = $translationData['content'] ?? $defaultContent;
            $translation->summary = $translationData['summary'] ?? $data['summary'] ?? null;
            $translation->seo_title = $translationData['seo_title'] ?? $data['seo_title'] ?? null;
            $translation->seo_description = $translationData['seo_description'] ?? $data['seo_description'] ?? null;
            $translation->seo_keywords = $translationData['seo_keywords'] ?? $data['seo_keywords'] ?? null;
            $translation->save();
        }

        return $article;
    }



    /**
     * 更新文章的某个语言版本
     *
     * @param string $lang 语言代码
     * @param array $data 要更新的数据
     * @return self
     */
    public function updateTranslation(string $lang, array $data): self
    {
        // 更新翻译内容
        $translation = $this->translateOrNew($lang);

        if (isset($data['title'])) {
            $translation->title = $data['title'];
        }

        if (isset($data['content'])) {
            $translation->content = $data['content'];
        }

        if (isset($data['summary'])) {
            $translation->summary = $data['summary'];
        }

        if (isset($data['seo_title'])) {
            $translation->seo_title = $data['seo_title'];
        }

        if (isset($data['seo_description'])) {
            $translation->seo_description = $data['seo_description'];
        }

        if (isset($data['seo_keywords'])) {
            $translation->seo_keywords = $data['seo_keywords'];
        }

        $this->save();

        // 如果是英文版本，同时更新主表的 title 和 content（冗余存储）
        if ($lang === 'en') {
            if (isset($data['title'])) {
                $this->title = $data['title'];
            }
            if (isset($data['content'])) {
                $this->content = $data['content'];
            }
            $this->save();
        }

        return $this;
    }
}
