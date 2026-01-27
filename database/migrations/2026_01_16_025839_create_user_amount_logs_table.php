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
        Schema::create('user_amount_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('user_id')->nullable()->default(0);
            $table->integer('total')->nullable()->default(0);
            $table->integer('after_amount')->nullable()->default(0);
            $table->integer('before_amount')->nullable()->default(0);
            $table->string('date_time')->nullable();
            $table->integer('type')->nullable()->default(2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_amount_logs');
    }
};
