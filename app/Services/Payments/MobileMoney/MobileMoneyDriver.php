<?php

namespace App\Services\Payments\MobileMoney;

use App\Models\Transaction;
use App\Services\Payments\PaymentDriverInterface;
use App\Services\Payments\Exceptions\PaymentFailedException;

class MobileMoneyDriver implements PaymentDriverInterface
{
    public function initiate(Transaction $transaction, array $data = []): array
    {
        // Simule un appel externe de mobile money
        if (!isset($data['phone'])) {
            throw new PaymentFailedException("Phone number is required for mobile money payment.");
        }

        // Simule OTP obligatoire
        return [
            'status' => 'pending_otp',
            'otp_required' => true,
            'reference' => 'MM-' . uniqid(),
        ];
    }

    public function confirm(Transaction $transaction): array
    {
        // Simule confirmation automatique
        if ($transaction->amount === 777) {
            return [
                'status' => 'failed',
                'reason' => 'Simulated failure on provider',
            ];
        }

        return [
            'status' => 'success',
            'provider_id' => 'MM-PAY-' . uniqid(),
        ];
    }
}
