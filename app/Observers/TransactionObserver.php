<?php

namespace App\Observers;

use App\Models\Fee;
use App\Models\Transaction;

class TransactionObserver
{

    public function creating(Transaction $transaction): void{
        // $this->updateTransactionTaxes($transaction);
    }

    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }



    protected function updateTransactionTaxes(Transaction $transaction){
        $merchantId = $transaction->merchant_id;

        // Recherche du fee applicable
        $fee = Fee::where('merchant_id', $merchantId)
            ->where('currency', $transaction->currency)
            ->where('scope', $transaction->scope ?? env('DEFAULT_ENVIRONMENT', 'sandbox'))
            ->where('min_amount', '<=', $transaction->amount)
            ->where('max_amount', '>=', $transaction->amount)
            ->first();

        if (!$fee) {
            // fallback : aucun fee → frais 0
            $transaction->fees = env('DEFAULT_FEE', 1.5);
            $transaction->net_amount = $transaction->amount;
            return;
        }

        // Calcul des frais
        $percentFee = ($transaction->amount * ($fee->percent / 100));
        $fixedFee   = $fee->fixed;

        $totalFees = (int) floor($percentFee + $fixedFee);

        // Affectation
        $transaction->fees = $totalFees;
        $transaction->net_amount = $transaction->amount - $totalFees;
    }

}
