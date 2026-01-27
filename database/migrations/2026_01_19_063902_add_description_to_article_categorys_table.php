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
        Schema::table('article_categorys', function (Blueprint $table) {
            $table->text('description')->nullable()->comment('分类描述')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_categorys', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
