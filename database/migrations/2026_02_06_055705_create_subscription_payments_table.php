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
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subscription_id')->comment('订阅ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->string('paypal_payment_id')->nullable()->comment('PayPal支付ID');
            $table->string('paypal_subscription_id')->index()->comment('PayPal订阅ID');
            $table->decimal('amount', 10)->comment('支付金额');
            $table->string('currency', 3)->default('USD')->comment('货币');
            $table->enum('payment_type', ['SETUP', 'REGULAR', 'TRIAL'])->default('REGULAR')->comment('支付类型');
            $table->enum('status', ['PENDING', 'COMPLETED', 'FAILED', 'CANCELLED', 'REFUNDED'])->default('PENDING')->comment('支付状态');
            $table->timestamp('billing_time')->comment('账单时间');
            $table->timestamp('paid_at')->nullable()->comment('支付完成时间');
            $table->json('paypal_response')->nullable()->comment('PayPal完整响应数据');
            $table->text('failure_reason')->nullable()->comment('失败原因');
            $table->timestamps();

            $table->index(['subscription_id', 'billing_time']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
