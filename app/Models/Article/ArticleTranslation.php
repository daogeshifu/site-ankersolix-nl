<?php

namespace App\Models;

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