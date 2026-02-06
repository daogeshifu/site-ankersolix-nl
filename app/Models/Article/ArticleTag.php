<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    use HasFactory;

    protected $table = 'article_tags';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * 获取使用该标签的所有文章
     */
    public function articles()
    {
        return $this->belongsToMany(
            Article::class,
            'article_tag_pivot',
            'article_tag_id',
            'article_id'
        );
    }
}
