<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ArticleCategory extends Model
{
    use HasFactory;

    // Keep the original fillable fields that aren't translatable
    protected $fillable = [
        'name', 
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        // 'parent_id',
    ];

    protected $table = 'article_categorys';

    protected $appends = ['count'];

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
