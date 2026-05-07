<?php

namespace App\Models\Article;


use App\Models\Article\ArticleCategory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArticleTask extends Model
{
    use HasFactory;

    protected $table = 'article_tasks';

    protected $fillable = [
        'keyword',
        'local_batch_no',
        'category_id',
        'writer_category',
        'writer_language',
        'info',
        'brand_info',
        'force_refresh',
        'include_cover',
        'content_image_count',
        'status',
        'remote_batch_id',
        'remote_task_id',
        'remote_status',
        'remote_request_payload',
        'remote_result_payload',
        'remote_created_at',
        'remote_last_polled_at',
        'result_synced_at',
        'article_id',
        'error_message',
        'fail_count',
        'scheduled_date',
    ];

    protected $casts = [
        'force_refresh'         => 'boolean',
        'include_cover'         => 'boolean',
        'content_image_count'   => 'integer',
        'remote_request_payload'=> 'array',
        'remote_result_payload' => 'array',
        'remote_created_at'     => 'datetime',
        'remote_last_polled_at' => 'datetime',
        'result_synced_at'      => 'datetime',
        'fail_count'            => 'integer',
        'scheduled_date'        => 'date',
    ];

    // 状态常量
    const STATUS_PENDING   = 0;  // 待处理
    const STATUS_TASK_GOT  = 2;  // 已提交远程并获取任务ID（batch_id + task_id）
    const STATUS_COMPLETED = 3;  // 已完成（文章已发布）
    const STATUS_FAILED    = -1; // 失败

    const DEFAULT_WRITER_CATEGORY = 'seo';
    const DEFAULT_WRITER_LANGUAGE = 'Netherlands';
    const DEFAULT_INCLUDE_COVER = true;
    const DEFAULT_CONTENT_IMAGE_COUNT = 3;

    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return static::statusOptions()[$this->status] ?? '未知';
    }

    public function getRemoteStatusLabelAttribute(): string
    {
        if (blank($this->remote_status)) {
            return '未同步';
        }

        return Str::headline((string) $this->remote_status);
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING   => '待同步',
            self::STATUS_TASK_GOT  => '远程处理中',
            self::STATUS_COMPLETED => '已发布',
            self::STATUS_FAILED    => '失败',
        ];
    }

    public static function writerCategoryOptions(): array
    {
        return [
            'seo' => 'SEO 文章',
            'geo' => 'GEO 文章',
        ];
    }

    public static function defaultAttributes(): array
    {
        return [
            'writer_category'     => config('services.article_task_default_category', self::DEFAULT_WRITER_CATEGORY),
            'writer_language'     => config('services.article_task_default_language', self::DEFAULT_WRITER_LANGUAGE),
            'force_refresh'       => (bool) config('services.article_task_default_force_refresh', false),
            'include_cover'       => (bool) config('services.article_task_default_include_cover', self::DEFAULT_INCLUDE_COVER),
            'content_image_count' => (int) config('services.article_task_default_image_count', self::DEFAULT_CONTENT_IMAGE_COUNT),
        ];
    }
}
