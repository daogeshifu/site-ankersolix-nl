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
        Schema::create('paypal_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('paypal_product_id')->unique()->comment('PayPal产品ID');
            $table->string('name')->comment('产品名称');
            $table->text('description')->comment('产品描述');
            $table->enum('type', ['PHYSICAL', 'DIGITAL', 'SERVICE'])->default('SERVICE')->comment('产品类型');
            $table->enum('category', ['SOFTWARE', 'DIGITAL_MEDIA_BOOKS_MOVIES_MUSIC', 'GAMES'])->default('SOFTWARE')->comment('产品分类');
            $table->string('home_url')->nullable()->comment('产品主页URL');
            $table->json('paypal_response')->nullable()->comment('PayPal完整响应数据');
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->comment('状态');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paypal_products');
    }
};
