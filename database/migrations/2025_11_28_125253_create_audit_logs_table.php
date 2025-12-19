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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_type', 255); // Increased length
            $table->bigInteger('user_id')->nullable();
            $table->uuid('merchant_id')->nullable();
            $table->string('event',50);
            $table->string('method', 10)->nullable(); // New: HTTP method (e.g., GET, POST)
            $table->string('path')->nullable(); // New: Full request path
            $table->integer('response_status')->nullable(); // New: HTTP response status
            $table->string('auditable_type',100)->nullable(); // Added nullable
            $table->string('auditable_id', 36)->nullable(); // Changed to string for UUIDs, and made nullable
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip',45)->nullable(); // Make nullable
            $table->text('user_agent')->nullable(); // Make nullable
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('merchant_id')->references('id')->on('merchants')->cascadeOnDelete();

            // Add indexes for performance
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['user_type', 'user_id']);
            $table->index('event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
