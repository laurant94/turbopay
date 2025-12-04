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

            $table->uuid("reference")->unique();

            $table->bigInteger('amount');
            $table->char('currency',3);
            $table->text('description')->nullable();
            $table->text('callback_url')->nullable();

            $table->bigInteger('fees')->default(0);
            $table->bigInteger('net_amount')->default(0);
            $table->string('status')->default('pending'); //'pending','successful','failed','refunded','partially_refunded','manual_review'
            $table->text('cancel_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->json("custom_data")->nullable();

            $table->string('payment_token')->nullable()->unique();
            $table->timestamp('payment_token_expires_at')->nullable();
            $table->string("provider")->nullable();

            $table->timestamps();
            $table->softDeletes();

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
