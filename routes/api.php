<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix("v1")->name("api.")
    ->middleware(['auth.apikey'])
    ->group(base_path("routes/payment/api.php"));