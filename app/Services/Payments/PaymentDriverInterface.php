<?php

namespace App\Services\Payments;

use App\Models\Transaction;

interface PaymentDriverInterface
{
    /**
     * Initialise un paiement chez le provider externe.
     */
    public function initiate(Transaction $transaction, array $data = []): array;

    /**
     * Vérifie le statut réel du paiement via le provider.
     */
    public function confirm(Transaction $transaction): array;
}
