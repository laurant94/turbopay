<?php

use App\Http\Controllers\User\CustomerController;
use App\Http\Controllers\User\TransactionController;
use Illuminate\Support\Facades\Route;

Route::resource("customers", CustomerController::class);
Route::resource("transactons", TransactionController::class);
