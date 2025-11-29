<?php

namespace App\Services\Fees;

use App\Models\Transaction;
use App\Models\Fee;

class FeeService
{
    public function applyFees(Transaction $transaction): array
    {
        $fee = Fee::where('merchant_id', $transaction->merchant_id)
            ->where('currency', $transaction->currency)
            ->where('min_amount', '<=', $transaction->amount)
            ->where('max_amount', '>=', $transaction->amount)
            ->first();

        if (! $fee) {
            return [0, $transaction->amount];
        }

        $percent = intval($transaction->amount * ($fee->percent / 100));
        $totalFee = $percent + $fee->fixed;

        return [$totalFee, $transaction->amount - $totalFee];
    }
}
