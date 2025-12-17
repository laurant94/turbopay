<?php

namespace App\Observers;

use App\Models\Fee;
use App\Models\Transaction;
use App\Models\Event; // Import the new Event model
use App\Http\Enums\EventType; // Import the EventType Enum
use App\Models\AuditLog; // Import the AuditLog model
use Illuminate\Support\Facades\Auth; // To get the authenticated user

class TransactionObserver
{

    public function creating(Transaction $transaction): void{
        $this->updateTransactionTaxes($transaction);
    }

    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        \App\Models\Event::create([
            'merchant_id' => $transaction->merchant_id,
            'user_id' => Auth::id(), // Assign the authenticated user's ID
            'event_type' => EventType::TransactionCreated, // Use the Enum
            'payload' => $transaction->load(['merchant', 'customer'])->toArray(),
        ]);
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        if ($transaction->isDirty()) { // Only log if something actually changed
            $changes = $transaction->getChanges();
            $oldValues = $transaction->getOriginal();

            AuditLog::create([
                'user_type' => \App\Models\User::class, // Assuming a User triggered the update
                'user_id' => Auth::id(),
                'merchant_id' => $transaction->merchant_id,
                'event' => 'transaction.updated',
                'auditable_type' => Transaction::class,
                'auditable_id' => $transaction->id,
                'old_values' => array_intersect_key($oldValues, $changes), // Only old values for changed attributes
                'new_values' => $changes, // Only new values for changed attributes
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
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
        $merchant = $transaction->merchant;

        // Déterminer qui paie les frais à partir des paramètres du marchand
        // Par défaut, le marchand paie.
        $feePayer = $merchant->settings()->get('merchant.fees.payer', 'merchant');

        // Recherche du fee applicable
        $fee = Fee::where('merchant_id', $merchant->id)
            ->where('currency', $transaction->currency)
            ->where('scope', $transaction->scope ?? env('DEFAULT_ENVIRONMENT', 'sandbox'))
            ->where('min_amount', '<=', $transaction->amount)
            ->where('max_amount', '>=', $transaction->amount)
            ->first();

        $totalFees = 0;

        if ($fee) {
            // Calcul des frais
            $percentFee = ($transaction->amount * ($fee->percent / 100));
            $fixedFee   = $fee->fixed;
            $totalFees = (int) floor($percentFee + $fixedFee);
        } else {
            // Fallback: utiliser un taux de frais par défaut si aucune règle n'est trouvée
            $defaultFeePercent = (float) env('DEFAULT_FEE', 1.5);
            $totalFees = (int) floor($transaction->amount * ($defaultFeePercent / 100));
        }

        $transaction->fees = $totalFees;

        if ($feePayer === 'customer') {
            // Le client paie les frais.
            // Le montant net pour le marchand est le montant de base de la transaction.
            $transaction->net_amount = $transaction->amount;
            // Le montant total de la transaction est augmenté des frais.
            $transaction->amount = $transaction->amount + $totalFees;
        } else {
            // Le marchand paie les frais (comportement par défaut).
            // Le montant net pour le marchand est le montant de la transaction moins les frais.
            $transaction->net_amount = $transaction->amount - $totalFees;
        }
    }

}
