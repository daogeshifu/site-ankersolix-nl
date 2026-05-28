<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'description_payload',
        'specifications',
        'seo_payload',
        'source_payload',
        'raw_payload',
        'product_payload',
    ];

    protected $casts = [
        'description_payload' => 'array',
        'specifications' => 'array',
        'seo_payload' => 'array',
        'source_payload' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
