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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('merchant_id');
            $table->string('holder_name');
            $table->enum('type',['bank','mobile_money']);
            $table->string('iban_or_number'); // à chiffrer via cast
            $table->string('swift_or_operator'); // à chiffrer via cast
            $table->string('country',2);
            $table->string('status')->default('unverified'); //'unverified','verified','invalid'
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('merchant_id')->references('id')->on('merchants')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
