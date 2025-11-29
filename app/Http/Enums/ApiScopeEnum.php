<?php

namespace App\Http\Enums;

enum ApiScopeEnum: string
{
    // Customers
    case CUSTOMERS_READ = 'customers.read';
    case CUSTOMERS_WRITE = 'customers.write';
    case CUSTOMERS_UPDATE = 'customers.update';
    case CUSTOMERS_DELETE = 'customers.delete';

    // Transactions
    case TRANSACTIONS_READ = 'transactions.read';
    case TRANSACTIONS_WRITE = 'transactions.write';
    case TRANSACTIONS_UPDATE = 'transactions.update';
    case TRANSACTIONS_DELETE = 'transactions.delete';
    case TRANSACTIONS_TOKEN = 'transactions.token';     // generate payment token
    case TRANSACTIONS_PROCESS = 'transactions.process'; // send transaction for payment

    // Payouts
    case PAYOUTS_READ = 'payouts.read';
    case PAYOUTS_WRITE = 'payouts.write';
    case PAYOUTS_UPDATE = 'payouts.update';
    case PAYOUTS_DELETE = 'payouts.delete';
    case PAYOUTS_START = 'payouts.start';

    // Events
    case EVENTS_READ = 'events.read';

    // Balances
    case BALANCES_READ = 'balances.read';

    // Currencies
    case CURRENCIES_READ = 'currencies.read';

    // Logs
    case LOGS_READ = 'logs.read';

    // Webhooks
    case WEBHOOKS_READ = 'webhooks.read';

    public static function publicScopes(): array
    {
        return [
            // Customers
            self::CUSTOMERS_READ->value,
            self::CUSTOMERS_WRITE->value,

            // Transactions
            self::TRANSACTIONS_READ->value,
            self::TRANSACTIONS_WRITE->value,
            self::TRANSACTIONS_TOKEN->value,
            self::TRANSACTIONS_PROCESS->value,

            // Events
            self::EVENTS_READ->value,

            // Currencies
            self::CURRENCIES_READ->value,
        ];
    }

    /**
     * Scopes accessibles avec une clé PRIVATE.
     * -> Accès complet au compte marchand.
     */
    public static function privateScopes(): array
    {
        return array_map(
            fn ($case) => $case->value,
            self::cases()
        );
    }

}
