<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'external_variant_id',
        'sku',
        'title',
        'option1',
        'option2',
        'option3',
        'price',
        'compare_at_price',
        'currency',
        'available',
        'featured_image',
        'raw_payload',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'available' => 'boolean',
        'featured_image' => 'array',
        'raw_payload' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
