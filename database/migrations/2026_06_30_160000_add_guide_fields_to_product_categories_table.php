<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('product_categories', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->index()->after('seo_keywords');
            }

            if (!Schema::hasColumn('product_categories', 'related_article_ids')) {
                $table->json('related_article_ids')->nullable()->after('parent_id');
            }

            if (!Schema::hasColumn('product_categories', 'related_faq_ids')) {
                $table->json('related_faq_ids')->nullable()->after('related_article_ids');
            }

            if (!Schema::hasColumn('product_categories', 'quick_answer')) {
                $table->json('quick_answer')->nullable()->after('related_faq_ids');
            }

            if (!Schema::hasColumn('product_categories', 'page_data')) {
                $table->json('page_data')->nullable()->after('quick_answer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $columns = [
                'parent_id',
                'related_article_ids',
                'related_faq_ids',
                'quick_answer',
                'page_data',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('product_categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
