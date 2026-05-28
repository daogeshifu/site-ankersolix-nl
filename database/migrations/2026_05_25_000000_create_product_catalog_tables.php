<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 160);
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();
            $table->string('seo_title', 255)->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->unsignedBigInteger('crawl_id')->nullable()->index();
            $table->string('source_site', 190)->nullable()->index();
            $table->string('platform', 80)->nullable();
            $table->string('external_product_id', 190)->nullable()->index();
            $table->string('selected_variant_id', 190)->nullable();
            $table->string('handle', 255)->nullable();
            $table->string('url_key', 255)->nullable();
            $table->string('slug', 255)->unique();
            $table->string('site_url', 1024)->nullable();
            $table->string('input_url', 1024)->nullable();
            $table->string('final_url', 1024)->nullable();
            $table->string('vendor', 255)->nullable();
            $table->string('brand', 255)->nullable()->index();
            $table->string('title', 500)->index();
            $table->string('currency', 8)->default('EUR');
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->decimal('min_variant_price', 12, 2)->nullable();
            $table->decimal('max_variant_price', 12, 2)->nullable();
            $table->string('availability_status', 80)->nullable()->index();
            $table->boolean('selected_variant_available')->default(false);
            $table->boolean('any_variant_available')->default(false);
            $table->string('product_type', 190)->nullable()->index();
            $table->string('source_category', 190)->nullable();
            $table->json('tags')->nullable();
            $table->string('main_image', 1024)->nullable();
            $table->string('featured_image', 1024)->nullable();
            $table->text('summary')->nullable();
            $table->longText('description_text')->nullable();
            $table->longText('description_html')->nullable();
            $table->string('seo_title', 255)->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->string('html_sha256', 128)->nullable();
            $table->string('product_hash', 128)->nullable()->index();
            $table->timestamp('crawled_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['source_site', 'external_product_id', 'selected_variant_id'], 'products_source_product_variant_unique');
        });

        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->json('description_payload')->nullable();
            $table->json('specifications')->nullable();
            $table->json('seo_payload')->nullable();
            $table->json('source_payload')->nullable();
            $table->longText('raw_payload')->nullable();
            $table->longText('product_payload')->nullable();
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('external_variant_id', 190)->nullable()->index();
            $table->string('sku', 190)->nullable();
            $table->string('title', 500)->nullable();
            $table->string('option1', 255)->nullable();
            $table->string('option2', 255)->nullable();
            $table->string('option3', 255)->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->string('currency', 8)->default('EUR');
            $table->boolean('available')->default(false);
            $table->json('featured_image')->nullable();
            $table->json('raw_payload')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('type', 30)->default('image');
            $table->string('url', 1024);
            $table->string('alt', 500)->nullable();
            $table->string('source', 120)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_media');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_details');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
