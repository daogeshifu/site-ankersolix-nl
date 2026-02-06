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
        Schema::create('user_orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('trade_no', 30)->default('')->unique('order_num')->comment('订单号');
            $table->integer('user_id')->default(0)->index('user_id')->comment('用户ID');
            $table->integer('download_id')->default(0)->index('book_id')->comment('资源ID');
            $table->boolean('download_type')->default(true)->comment('资源类型');
            $table->decimal('pay_amount', 10)->default(0)->comment('支付金额(道格币)');
            $table->boolean('order_type')->default(true)->index('order_type_2')->comment('消费类型 1下载，2充值；');
            $table->string('buyer_logon_id', 30)->default('')->comment('支付宝用户');
            $table->string('trade_status', 20)->default('')->index('trade_status')->comment('支付状态');
            $table->dateTime('send_pay_date')->nullable()->index('send_pay_date')->comment('付款时间');
            $table->boolean('pay_type')->default(false)->index('pay_type')->comment('支付方式 0 支付宝；1微信；2 余额');
            $table->string('remarks')->default('')->comment('备注');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_orders');
    }
};
