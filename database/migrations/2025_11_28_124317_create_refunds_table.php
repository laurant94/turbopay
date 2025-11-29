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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->uuid('merchant_id');
            $table->uuid('transaction_id');
            $table->bigInteger('amount');
            $table->char('currency',3);
            $table->text('reason');
            $table->string('status')->default('pending'); //'pending','processed','failed'
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('merchant_id')->references('id')->on('merchants')->cascadeOnDelete();
            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
