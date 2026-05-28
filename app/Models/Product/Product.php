<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'crawl_id',
        'source_site',
        'platform',
        'external_product_id',
        'selected_variant_id',
        'handle',
        'url_key',
        'slug',
        'site_url',
        'input_url',
        'final_url',
        'vendor',
        'brand',
        'title',
        'currency',
        'price',
        'compare_at_price',
        'min_variant_price',
        'max_variant_price',
        'availability_status',
        'selected_variant_available',
        'any_variant_available',
        'product_type',
        'source_category',
        'tags',
        'main_image',
        'featured_image',
        'summary',
        'description_text',
        'description_html',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'html_sha256',
        'product_hash',
        'crawled_at',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'tags' => 'array',
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'min_variant_price' => 'decimal:2',
        'max_variant_price' => 'decimal:2',
        'selected_variant_available' => 'boolean',
        'any_variant_available' => 'boolean',
        'is_active' => 'boolean',
        'crawled_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function detail()
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function images()
    {
        return $this->hasMany(ProductMedia::class)->where('type', 'image')->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDisplayImageAttribute(): ?string
    {
        return $this->main_image ?: $this->featured_image;
    }

    public function getDisplayPriceAttribute(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        return trim(($this->currency ?: 'EUR') . ' ' . number_format((float) $this->price, 2, ',', '.'));
    }
}
