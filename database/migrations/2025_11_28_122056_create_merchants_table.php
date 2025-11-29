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
        Schema::create('merchants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('registration_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('address')->nullable();
            $table->string('country',2);
            $table->string('status')->default('pending');
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id','name']);

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
