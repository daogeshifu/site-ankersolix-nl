<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class ArticleTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'locale',
        'title',
        'summary',
        'content',
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];
} 