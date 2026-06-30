<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ProductCategory extends Model
{
    use HasFactory;

    private static ?bool $hasParentColumn = null;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'parent_id',
        'related_article_ids',
        'related_faq_ids',
        'quick_answer',
        'page_data',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'parent_id' => 'integer',
        'related_article_ids' => 'array',
        'related_faq_ids' => 'array',
        'quick_answer' => 'array',
        'page_data' => 'array',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * 本分类 + 所有后代分类的 id(用于父/聚合分类页聚合子分类商品)。
     */
    public function descendantIds(): array
    {
        if (!self::supportsHierarchy()) {
            return [$this->id];
        }

        $childrenByParent = [];
        foreach (self::query()->get(['id', 'parent_id']) as $cat) {
            $childrenByParent[$cat->parent_id][] = $cat->id;
        }

        $ids = [];
        $stack = [$this->id];
        while ($stack) {
            $id = array_pop($stack);
            if (isset($ids[$id])) {
                continue;
            }
            $ids[$id] = true;
            foreach ($childrenByParent[$id] ?? [] as $childId) {
                $stack[] = $childId;
            }
        }

        return array_keys($ids);
    }

    public static function supportsHierarchy(): bool
    {
        if (self::$hasParentColumn !== null) {
            return self::$hasParentColumn;
        }

        self::$hasParentColumn = Schema::hasColumn('product_categories', 'parent_id');

        return self::$hasParentColumn;
    }
}
