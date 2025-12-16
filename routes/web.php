<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\Web\TransactionController;
use App\Http\Middleware\VerifyUserMerchantAvailability;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('momo-payment/{token}', [TransactionController::class, "momoPayment"]);
Route::post("process", [TransactionController::class, "process"])->name("process");
Route::get("confirm/{id}", [TransactionController::class, "confirm"])->name("confirm");
Route::post("process/customer/{customer}/update", [TransactionController::class, "updateCustomer"])
    ->name("process.update_customer");
Route::post("process/create-customer", [TransactionController::class, "createCustomer"])
    ->name("process.create_customer");
Route::get("payer-payment/callback", function(){
    return "ok";
})->name("default-callback");



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    route::name('user.')
    ->middleware(VerifyUserMerchantAvailability::class)
    ->prefix("merchant")
        ->group(base_path("routes/user/web.php"));

});
