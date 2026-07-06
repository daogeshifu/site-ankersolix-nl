<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('product_categories', 'is_show')) {
                $table->boolean('is_show')->default(true)->after('is_active');
            }
        });

        DB::table('product_categories')
            ->whereNull('is_show')
            ->update(['is_show' => true]);
    }

    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            if (Schema::hasColumn('product_categories', 'is_show')) {
                $table->dropColumn('is_show');
            }
        });
    }
};
