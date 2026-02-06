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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('plan_id')->index('user_subscriptions_plan_id_foreign')->comment('订阅计划ID');
            $table->string('paypal_subscription_id')->unique()->comment('PayPal订阅ID');
            $table->string('paypal_plan_id')->comment('PayPal计划ID');
            $table->string('subscriber_name')->comment('订阅者姓名');
            $table->string('subscriber_email')->comment('订阅者邮箱');
            $table->decimal('amount', 10)->comment('订阅金额');
            $table->string('currency', 3)->default('USD')->comment('货币');
            $table->enum('status', ['APPROVAL_PENDING', 'APPROVED', 'ACTIVE', 'SUSPENDED', 'CANCELLED', 'EXPIRED'])->default('APPROVAL_PENDING')->comment('订阅状态');
            $table->timestamp('start_time')->nullable()->comment('订阅开始时间');
            $table->timestamp('next_billing_time')->nullable()->comment('下次扣费时间');
            $table->json('paypal_response')->nullable()->comment('PayPal完整响应数据');
            $table->json('billing_info')->nullable()->comment('账单信息');
            $table->text('cancel_reason')->nullable()->comment('取消原因');
            $table->timestamp('cancelled_at')->nullable()->comment('取消时间');
            $table->timestamps();

            $table->index(['status', 'next_billing_time']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
