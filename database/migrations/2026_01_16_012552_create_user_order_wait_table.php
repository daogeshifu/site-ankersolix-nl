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
        Schema::create('user_order_wait', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('trade_no', 30)->default('')->unique('order_num')->comment('订单号');
            $table->integer('user_id')->default(0)->index('user_id')->comment('用户ID');
            $table->integer('download_id')->default(0)->index('book_id')->comment('资源ID');
            $table->boolean('download_type')->default(false)->index('chapter_id')->comment('资源类型');
            $table->decimal('pay_amount', 10)->default(0)->comment('支付金额');
            $table->boolean('order_type')->default(false)->index('order_type')->comment('消费类型 0下载，1 永久；2 年；3 月');
            $table->string('trade_status', 20)->default('WAIT')->index('trade_status')->comment('支付状态');
            $table->integer('check_num')->default(0)->comment('检测次数；最大次数');
            $table->boolean('pay_type')->default(false)->comment('支付方式 0 支付宝；1微信；2 余额');
            $table->string('remarks')->default('')->comment('备注');
            $table->boolean('status')->default(false)->index('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_order_wait');
    }
};
