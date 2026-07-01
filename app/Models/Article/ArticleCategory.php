<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'parent_id',
        'is_active',
        'related_product_ids',
        'related_faq_ids',
        'quick_answer',
        'page_data',
    ];

    protected $table = 'article_categorys';

    protected $appends = ['count'];

    protected $casts = [
        'parent_id' => 'integer',
        'is_active' => 'boolean',
        'related_product_ids' => 'array',
        'related_faq_ids' => 'array',
        'quick_answer' => 'array',
        'page_data' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 获取子分类
     */
    public function children()
    {
        return $this->hasMany(ArticleCategory::class, 'parent_id');
    }

    /**
     * 获取父分类
     */
    public function parent()
    {
        return $this->belongsTo(ArticleCategory::class, 'parent_id');
    }

    /**
     * 分类下的所有文章
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id', 'id');
    }

    public function getCountAttribute()
    {
        return $this->articles()->count();
    }
}
