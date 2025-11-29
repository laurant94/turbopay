<?php

namespace App\Services\Payments;

use App\Models\Transaction;
use App\Services\Payments\MobileMoney\MobileMoneyDriver;
use App\Services\Fees\FeeService;
use App\Services\Webhooks\WebhookService;
use App\Services\AntiFraud\AntiFraudService;
use App\Services\Payments\Exceptions\InvalidPaymentModeException;
use App\Services\Payments\Exceptions\InvalidTransactionException;

class PaymentService
{
    public function __construct(
        protected FeeService $feeService,
        protected AntiFraudService $fraudService,
        protected WebhookService $webhookService
    ) {}

    /**
     * Génère un intent de paiement avant envoi mobile-money.
     */
    public function createIntent(Transaction $transaction): Transaction
    {
        if ($transaction->status !== 'pending') {
            throw new InvalidTransactionException("Transaction is not pending.");
        }

        // Anti-fraude
        $this->fraudService->check($transaction);

        // Appliquer les frais
        [$fees, $net] = $this->feeService->applyFees($transaction);
        $transaction->fees = $fees;
        $transaction->net_amount = $net;
        $transaction->save();

        return $transaction;
    }

    /**
     * Lance un paiement mobile-money / carte selon mode.
     */
    public function process(Transaction $transaction, string $mode, array $data = []): array
    {
        $driver = $this->resolveDriver($mode);

        // Step 1 : Appel provider
        $response = $driver->initiate($transaction, $data);

        if ($response['status'] === 'pending_otp') {
            return $response; // Retourne OTP au frontend
        }

        return $this->confirm($transaction, $mode);
    }

    /**
     * Confirme un paiement.
     */
    public function confirm(Transaction $transaction, string $mode): array
    {
        $driver = $this->resolveDriver($mode);
        $response = $driver->confirm($transaction);

        if ($response['status'] === 'failed') {
            $transaction->status = 'failed';
            $transaction->save();

            $this->webhookService->dispatch('payment.failed', $transaction);

            return $response;
        }

        $transaction->status = 'successful';
        $transaction->paid_at = now();
        $transaction->save();

        $this->webhookService->dispatch('payment.success', $transaction);

        return $response;
    }

    /**
     * Retourne le driver correspondant au mode choisi.
     */
    public function resolveDriver(string $mode): PaymentDriverInterface
    {
        return match ($mode) {
            'mobile' => new MobileMoneyDriver(),
            //'card'   => new CardDriver(),
            default  => throw new InvalidPaymentModeException("Invalid payment mode: $mode")
        };
    }
}
