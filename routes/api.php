<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("v1")->name("api.")
    ->middleware(['auth.apikey', \App\Http\Middleware\LogApiActions::class])
    ->group(base_path("routes/payment/api.php"));

Route::prefix("v1")->name("api.")
    ->group(base_path("routes/payment/process.php"));