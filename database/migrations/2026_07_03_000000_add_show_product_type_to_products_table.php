<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'show_product_type')) {
                $table->boolean('show_product_type')
                    ->default(true)
                    ->after('product_type')
                    ->index()
                    ->comment('是否在前台展示产品类型');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'show_product_type')) {
                $table->dropColumn('show_product_type');
            }
        });
    }
};
