<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Models\User\User;
use Illuminate\Support\Facades\Cache;

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

    /**
     * Keep the original fillable fields that aren't translatable
     * title 和 content 在主表中冗余存储荷兰语版本
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'link',
        'keywords',
        'author',
        'author_bio',
        'cover',
        'ai_cover',
        'images_processed',
        'title',
        'content'
    ];

    /**
     * 类型转换
     * @var array
     */
    protected $casts = [
        'view_count' => 'integer',
        'read_count' => 'integer',
        'images_processed' => 'boolean',
        'last_viewed_at' => 'datetime',
        'last_read_at' => 'datetime',
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
     * 文章标签（多对多）
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(
            ArticleTag::class,
            'article_tag_pivot',
            'article_id',
            'article_tag_id'
        );
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (empty($this->cover)) {
            return null;
        }

        $cover = ltrim($this->cover, '/');

        if (str_starts_with($cover, 'upload/')) {
            return asset('storage/' . $cover);
        }

        return $this->cover;
    }

    /**
     * 创建多语言文章
     *
     * @param array $data 文章数据
     * @param string $lang 语言代码 (nl, en)
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
        $translation->save();

        // 如果是荷兰语版本，同时更新主表的 title 和 content（冗余存储）
        // 注意：不能用 $article->title = ... ，Translatable 会代理到当前 locale 的翻译行
        if ($lang === 'nl') {
            self::where('id', $article->id)->update([
                'title' => $data['title'],
                'content' => $data['content'],
            ]);
        }

        return $article;
    }

    /**
     * 创建多语言文章
     *
     * @param array $data 文章主数据（title/content/summary等字段）
     * @param array $locales 要创建的语言列表，格式：
     *                       [
     *                          'nl' => ['title' => '荷兰语标题', ...],
     *                          'en' => ['title' => '英文标题', ...],
     *                       ]
     *                       如果是索引数组 ['nl', 'en']，则使用主表 title/content 填充
     * @return self
     * @throws \Exception
     */
    public static function createWithTranslations(array $data, array $locales = ['nl', 'en']): self
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

        $translation->save();

        // 如果是荷兰语版本，同时更新主表的 title 和 content（冗余存储）
        // 注意：不能用 $this->title = ... ，Translatable 会代理到当前 locale 的翻译行
        if ($lang === 'nl') {
            $updates = [];
            if (isset($data['title'])) {
                $updates['title'] = $data['title'];
            }
            if (isset($data['content'])) {
                $updates['content'] = $data['content'];
            }
            if (!empty($updates)) {
                self::where('id', $this->id)->update($updates);
            }
        }

        return $this;
    }



    /**
     * 记录一次浏览（宽松规则）
     * IP + 5 分钟去重
     * 
     * @param string $ip IP 地址
     * @param int $ttlSeconds 缓存有效期（秒）
     * @return void
     */
    public function recordViewByIp(string $ip, int $ttlSeconds = 300): void
    {
        $cacheKey = $this->viewCacheKey($ip);

        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, $ttlSeconds);

        $this->increment('view_count', 1, [
            'last_viewed_at' => now(),
        ]);
    }

    /**
     * 记录一次有效阅读（一般严格规则）
     * 依赖前端已完成停留 / 滚动判断
     * 
     * @param string $ip IP 地址
     * @param int $ttlSeconds 缓存有效期（秒）
     * @return void
     */
    public function recordReadByIp(string $ip, int $ttlSeconds = 300): void
    {
        $cacheKey = $this->readCacheKey($ip);

        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, $ttlSeconds);

        $this->increment('read_count', 1, [
            'last_read_at' => now(),
        ]);
    }

    /* ---------------- Cache Key 规范 ---------------- */

    protected function viewCacheKey(string $ip): string
    {
        return "article:{$this->id}:view:{$ip}";
    }

    protected function readCacheKey(string $ip): string
    {
        return "article:{$this->id}:read:{$ip}";
    }

}
