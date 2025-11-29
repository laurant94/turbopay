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
        Schema::create('currency_limits', function (Blueprint $table) {
            $table->id();
            $table->char('currency',3);
            $table->bigInteger('max_amount');
            $table->integer('daily_count');
            $table->bigInteger('daily_sum');
            $table->enum('scope',['sandbox','live']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_limits');
    }
};
