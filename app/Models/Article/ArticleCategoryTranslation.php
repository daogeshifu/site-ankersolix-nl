<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class ArticleCategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];
} 