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
            if (!Schema::hasColumn('articles', 'product_widget_image')) {
                $table->string('product_widget_image', 640)->nullable()->comment('文章产品卡片图片');
            }

            if (!Schema::hasColumn('articles', 'product_widget_title')) {
                $table->string('product_widget_title', 255)->nullable()->comment('文章产品卡片标题');
            }

            if (!Schema::hasColumn('articles', 'product_widget_price')) {
                $table->string('product_widget_price', 255)->nullable()->comment('文章产品卡片价格');
            }

            if (!Schema::hasColumn('articles', 'product_widget_description')) {
                $table->text('product_widget_description')->nullable()->comment('文章产品卡片描述');
            }

            if (!Schema::hasColumn('articles', 'product_widget_more_label')) {
                $table->string('product_widget_more_label', 255)->nullable()->comment('文章产品卡片按钮一文案');
            }

            if (!Schema::hasColumn('articles', 'product_widget_more_url')) {
                $table->string('product_widget_more_url', 640)->nullable()->comment('文章产品卡片按钮一链接');
            }

            if (!Schema::hasColumn('articles', 'product_widget_buy_label')) {
                $table->string('product_widget_buy_label', 255)->nullable()->comment('文章产品卡片按钮二文案');
            }

            if (!Schema::hasColumn('articles', 'product_widget_buy_url')) {
                $table->string('product_widget_buy_url', 640)->nullable()->comment('文章产品卡片按钮二链接');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            foreach ([
                'product_widget_image',
                'product_widget_title',
                'product_widget_price',
                'product_widget_description',
                'product_widget_more_label',
                'product_widget_more_url',
                'product_widget_buy_label',
                'product_widget_buy_url',
            ] as $column) {
                if (Schema::hasColumn('articles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
