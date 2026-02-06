<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('article_category_translations', function (Blueprint $table) {
            $table->foreign(['article_category_id'])->references(['id'])->on('article_categorys')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_category_translations', function (Blueprint $table) {
            $table->dropForeign('article_category_translations_article_category_id_foreign');
        });
    }
};
