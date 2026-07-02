<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'is_front_visible')) {
                $table->boolean('is_front_visible')
                    ->default(true)
                    ->after('images_processed')
                    ->comment('是否在前台入口展示');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'is_front_visible')) {
                $table->dropColumn('is_front_visible');
            }
        });
    }
};
