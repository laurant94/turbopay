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
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_endpoint_id')->constrained('webhook_endpoints')->cascadeOnDelete();
            $table->uuid('merchant_id');
            $table->uuid('transaction_id')->nullable();
            $table->string('event',40);
            $table->json('payload');
            $table->timestamp('sent_at')->nullable();
            $table->smallInteger('response_status')->nullable();
            $table->tinyInteger('attempts')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('merchant_id')->references('id')->on('merchants')->cascadeOnDelete();
            $table->foreign('transaction_id')->references('id')->on('transactions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
    }
};
