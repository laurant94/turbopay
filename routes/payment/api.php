<?php

use App\Http\Enums\ApiScopeEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PayoutController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TransactionController;


/*
|--------------------------------------------------------------------------
| Customers
|--------------------------------------------------------------------------
*/
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])
        ->middleware('require.scope:' . ApiScopeEnum::CUSTOMERS_READ->value);

    Route::post('', [CustomerController::class, 'store'])
        ->middleware('require.scope:' . ApiScopeEnum::CUSTOMERS_WRITE->value);

    Route::get('/{id}', [CustomerController::class, 'show'])
        ->middleware('require.scope:' . ApiScopeEnum::CUSTOMERS_READ->value);

    Route::put('/{id}', [CustomerController::class, 'update'])
        ->middleware('require.scope:' . ApiScopeEnum::CUSTOMERS_UPDATE->value);

    Route::delete('/{id}', [CustomerController::class, 'destroy'])
        ->middleware('require.scope:' . ApiScopeEnum::CUSTOMERS_DELETE->value);
});




/*
|--------------------------------------------------------------------------
| Transactions
|--------------------------------------------------------------------------
*/
Route::prefix('transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])
        ->middleware('require.scope:' . ApiScopeEnum::TRANSACTIONS_READ->value);

    Route::post('/', [TransactionController::class, 'store'])
        ->middleware('require.scope:' . ApiScopeEnum::TRANSACTIONS_WRITE->value);

    Route::get('/{id}', [TransactionController::class, 'show'])
        ->middleware('require.scope:' . ApiScopeEnum::TRANSACTIONS_READ->value);

    Route::put('/{id}', [TransactionController::class, 'update'])
        ->middleware('require.scope:' . ApiScopeEnum::TRANSACTIONS_UPDATE->value);

    Route::delete('/{id}', [TransactionController::class, 'destroy'])
        ->middleware('require.scope:' . ApiScopeEnum::TRANSACTIONS_DELETE->value);

    // POST /transactions/{id}/token
    Route::post('/{id}/token', [TransactionController::class, 'token'])
        ->middleware('require.scope:' . ApiScopeEnum::TRANSACTIONS_TOKEN->value);

    // POST /transactions/{mode}
    Route::post('/process/{mode}', [TransactionController::class, 'process'])
        ->middleware('require.scope:' . ApiScopeEnum::TRANSACTIONS_PROCESS->value);
});




/*
|--------------------------------------------------------------------------
| Payouts
|--------------------------------------------------------------------------
*/
Route::prefix('payouts')->group(function () {
    Route::get('/', [PayoutController::class, 'index'])
        ->middleware('require.scope:' . ApiScopeEnum::PAYOUTS_READ->value);

    Route::post('/', [PayoutController::class, 'store'])
        ->middleware('require.scope:' . ApiScopeEnum::PAYOUTS_WRITE->value);

    Route::get('/{id}', [PayoutController::class, 'show'])
        ->middleware('require.scope:' . ApiScopeEnum::PAYOUTS_READ->value);

    Route::put('/{id}', [PayoutController::class, 'update'])
        ->middleware('require.scope:' . ApiScopeEnum::PAYOUTS_UPDATE->value);

    Route::delete('/{id}', [PayoutController::class, 'destroy'])
        ->middleware('require.scope:' . ApiScopeEnum::PAYOUTS_DELETE->value);

    // PUT /payouts/start
    Route::put('/start/{id}', [PayoutController::class, 'start'])
        ->middleware('require.scope:' . ApiScopeEnum::PAYOUTS_START->value);
});




 /*
|--------------------------------------------------------------------------
| Events
|--------------------------------------------------------------------------
*/
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index'])
        ->middleware('require.scope:' . ApiScopeEnum::EVENTS_READ->value);

    Route::get('/{id}', [EventController::class, 'show'])
        ->middleware('require.scope:' . ApiScopeEnum::EVENTS_READ->value);
});





 /*
|--------------------------------------------------------------------------
| Balances
|--------------------------------------------------------------------------
*/
Route::prefix('balances')->group(function () {
    Route::get('/', [BalanceController::class, 'index'])
        ->middleware('require.scope:' . ApiScopeEnum::BALANCES_READ->value);

    Route::get('/{currency}', [BalanceController::class, 'show'])
        ->middleware('require.scope:' . ApiScopeEnum::BALANCES_READ->value);
});




/*
|--------------------------------------------------------------------------
| Currencies
|--------------------------------------------------------------------------
*/
Route::prefix('currencies')->group(function () {
    Route::get('/', [CurrencyController::class, 'index'])
        ->middleware('require.scope:' . ApiScopeEnum::CURRENCIES_READ->value);

    Route::get('/{code}', [CurrencyController::class, 'show'])
        ->middleware('require.scope:' . ApiScopeEnum::CURRENCIES_READ->value);
});





/*
|--------------------------------------------------------------------------
| Logs
|--------------------------------------------------------------------------
*/
Route::prefix('logs')->group(function () {
    Route::get('/', [LogController::class, 'index'])
        ->middleware('require.scope:' . ApiScopeEnum::LOGS_READ->value);

    Route::get('/{id}', [LogController::class, 'show'])
        ->middleware('require.scope:' . ApiScopeEnum::LOGS_READ->value);
});





/*
|--------------------------------------------------------------------------
| Webhooks
|--------------------------------------------------------------------------
*/
Route::prefix('webhooks')->group(function () {
    Route::get('/', [WebhookController::class, 'index'])
        ->middleware('require.scope:' . ApiScopeEnum::WEBHOOKS_READ->value);

    Route::get('/{id}', [WebhookController::class, 'show'])
        ->middleware('require.scope:' . ApiScopeEnum::WEBHOOKS_READ->value);
});