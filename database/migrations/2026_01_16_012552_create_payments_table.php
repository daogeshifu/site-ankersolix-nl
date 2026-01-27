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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('用户ID（可为空，支持游客支付）');
            $table->string('paypal_order_id')->unique()->comment('PayPal订单ID');
            $table->string('paypal_transaction_id')->nullable()->comment('PayPal交易ID');
            $table->string('payer_id')->nullable()->comment('付款人ID');
            $table->string('payer_email')->nullable()->index()->comment('付款人邮箱');
            $table->decimal('amount', 10)->comment('支付金额');
            $table->string('currency', 3)->default('USD')->comment('货币');
            $table->string('description')->comment('支付描述');
            $table->enum('payment_method', ['paypal', 'card'])->default('paypal')->comment('支付方式');
            $table->enum('status', ['CREATED', 'SAVED', 'APPROVED', 'VOIDED', 'COMPLETED', 'PAYER_ACTION_REQUIRED'])->default('CREATED')->comment('支付状态');
            $table->string('intent')->default('CAPTURE')->comment('支付意图');
            $table->json('paypal_response')->nullable()->comment('PayPal完整响应数据');
            $table->json('items')->nullable()->comment('商品详情');
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->timestamps();

            $table->index(['status', 'paid_at']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
