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
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->uuid('merchant_id');
            $table->string('name');

            $table->string('key_prefix');
            $table->string('key_hash');
            $table->enum('type', ['public','secret'])->default('public');
            $table->json('scopes')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->boolean('active')->default(true);
            $table->string('note')->nullable();

            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants')->cascadeOnDelete();
            $table->index(['merchant_id']);

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
