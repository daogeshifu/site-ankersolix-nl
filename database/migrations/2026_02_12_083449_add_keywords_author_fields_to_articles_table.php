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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('keywords', 640)->nullable()->after('link')->comment('文章关键词');
            $table->string('author', 255)->nullable()->after('keywords')->comment('作者名称');
            $table->text('author_bio')->nullable()->after('author')->comment('作者简介');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['keywords', 'author', 'author_bio']);
        });
    }
};
