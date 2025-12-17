<?php

use App\Http\Controllers\User\ApiKeyController;
use App\Http\Controllers\User\CustomerController;
use App\Http\Controllers\User\MerchantController;
use App\Http\Controllers\User\MerchantSettingsController;
use App\Http\Controllers\User\TransactionController;
use Illuminate\Support\Facades\Route;

Route::resource("merchants", MerchantController::class);
Route::post('/merchants/{merchant}switch', [MerchantController::class, 'switch'])->name('merchants.switch');

Route::get('/merchants/{merchant}/settings', [MerchantSettingsController::class, 'show'])->name('merchants.settings.show');
Route::put('/merchants/{merchant}/settings', [MerchantSettingsController::class, 'update'])->name('merchants.settings.update');


Route::resource("customers", CustomerController::class);
Route::resource("transactions", TransactionController::class);
Route::post("transactions/{transaction}/generate-payment-token", [TransactionController::class, 'generatePaymentToken'])
    ->name("transactions.generate-token");

Route::resource("apiKeys", ApiKeyController::class)->only([
    'index', 'store',
]);
