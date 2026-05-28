<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('locale', 16)->default('nl')->index();
            $table->string('question', 500);
            $table->text('answer');
            $table->string('question_hash', 64);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('source', 32)->default('ai');
            $table->string('model', 64)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'question_hash'], 'product_faqs_product_question_hash_unique');
            $table->index(['product_id', 'is_active', 'sort_order'], 'product_faqs_product_active_sort_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_faqs');
    }
};
