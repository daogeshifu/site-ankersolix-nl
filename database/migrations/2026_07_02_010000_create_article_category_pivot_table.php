<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_category_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('article_category_id')->index('article_category_pivot_category_id_index');
            $table->timestamps();

            $table->unique(['article_id', 'article_category_id'], 'article_category_pivot_unique');
            $table->foreign('article_id')->references('id')->on('articles')->cascadeOnDelete();
            $table->foreign('article_category_id')->references('id')->on('article_categorys')->cascadeOnDelete();
        });

        DB::table('articles')
            ->join('article_categorys', 'articles.category_id', '=', 'article_categorys.id')
            ->whereNotNull('category_id')
            ->orderBy('articles.id')
            ->select(['articles.id', 'articles.category_id'])
            ->chunk(500, function ($articles) {
                $now = now();
                $rows = $articles
                    ->filter(fn ($article) => !empty($article->category_id))
                    ->map(fn ($article) => [
                        'article_id' => $article->id,
                        'article_category_id' => $article->category_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                    ->all();

                if ($rows !== []) {
                    DB::table('article_category_pivot')->insertOrIgnore($rows);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_category_pivot');
    }
};
