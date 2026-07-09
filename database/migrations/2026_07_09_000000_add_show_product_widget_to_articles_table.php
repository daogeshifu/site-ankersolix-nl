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
            if (!Schema::hasColumn('articles', 'hide_product_widget')) {
                $table->boolean('hide_product_widget')
                    ->default(true)
                    ->comment('是否隐藏文章产品卡片');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'hide_product_widget')) {
                $table->dropColumn('hide_product_widget');
            }
        });
    }
};
