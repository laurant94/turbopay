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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('merchant_id');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->bigInteger('amount');
            $table->char('currency',3);
            $table->bigInteger('fees')->default(0);
            $table->bigInteger('net_amount')->default(0);
            $table->string('status')->default('pending'); //'pending','successful','failed','refunded','partially_refunded','manual_review'
            $table->text('description')->nullable();
            $table->text('return_url')->nullable();
            $table->text('cancel_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants')->cascadeOnDelete();
            $table->index(['merchant_id','status']);
            $table->unique('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
