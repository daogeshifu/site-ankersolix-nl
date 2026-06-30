<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('article_categorys', function (Blueprint $table) {
            $table->json('related_product_ids')->nullable()->after('seo_keywords');
            $table->json('related_faq_ids')->nullable()->after('related_product_ids');
            $table->json('quick_answer')->nullable()->after('related_faq_ids');
            $table->json('page_data')->nullable()->after('quick_answer');
        });
    }

    public function down(): void
    {
        Schema::table('article_categorys', function (Blueprint $table) {
            $table->dropColumn([
                'related_product_ids',
                'related_faq_ids',
                'quick_answer',
                'page_data',
            ]);
        });
    }
};
