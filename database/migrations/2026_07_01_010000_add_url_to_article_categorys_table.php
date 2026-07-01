<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('article_categorys', function (Blueprint $table) {
            $table->string('url', 180)->nullable()->index()->after('name');
        });

        $used = [];
        DB::table('article_categorys')
            ->orderBy('id')
            ->get(['id', 'name'])
            ->each(function ($category) use (&$used) {
                $base = Str::slug((string) $category->name) ?: 'category-' . $category->id;
                $url = $base;
                $suffix = 2;

                while (isset($used[$url])) {
                    $url = $base . '-' . $suffix++;
                }

                $used[$url] = true;
                DB::table('article_categorys')
                    ->where('id', $category->id)
                    ->update(['url' => $url]);
            });
    }

    public function down(): void
    {
        Schema::table('article_categorys', function (Blueprint $table) {
            $table->dropIndex(['url']);
            $table->dropColumn('url');
        });
    }
};
