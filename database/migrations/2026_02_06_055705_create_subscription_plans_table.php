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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index('subscription_plans_product_id_foreign')->comment('关联产品ID');
            $table->string('paypal_plan_id')->unique()->comment('PayPal计划ID');
            $table->string('paypal_product_id')->comment('PayPal产品ID');
            $table->string('name')->comment('计划名称');
            $table->text('description')->comment('计划描述');
            $table->decimal('amount', 10)->comment('价格');
            $table->string('currency', 3)->default('USD')->comment('货币');
            $table->enum('interval_unit', ['DAY', 'WEEK', 'MONTH', 'YEAR'])->comment('计费周期单位');
            $table->integer('interval_count')->default(1)->comment('计费间隔');
            $table->decimal('setup_fee', 10)->nullable()->comment('设置费用');
            $table->integer('total_cycles')->default(0)->comment('总计费次数，0表示无限');
            $table->boolean('auto_bill_outstanding')->default(true)->comment('自动处理未付款');
            $table->integer('payment_failure_threshold')->default(3)->comment('支付失败阈值');
            $table->json('paypal_response')->nullable()->comment('PayPal完整响应数据');
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'CREATED'])->default('ACTIVE')->comment('状态');
            $table->timestamps();

            $table->index(['currency', 'interval_unit']);
            $table->index(['status', 'amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
