<?php

namespace App\Services\AntiFraud;

use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\CurrencyLimit;

class AntiFraudService{
    /**
     * Vérifie si la transaction respecte les limites anti-fraude.
     * Retourne un tableau : ['status' => ok|review|blocked, 'reason' => ...]
     */
    public function velocityCheck(Transaction $transaction): array
    {
        $limit = CurrencyLimit::where('currency', $transaction->currency)
            ->where('scope', $transaction->merchant->apiMode()) // sandbox|live
            ->first();

        if (!$limit) {
            return ['status' => 'ok']; // aucune règle définie
        }

        // 1. Montant max
        if ($transaction->amount > $limit->max_amount) {
            return [
                'status' => 'blocked',
                'reason' => 'Transaction amount exceeds maximum allowed limit.'
            ];
        }

        // 2. Stats du jour
        $today = Carbon::now()->startOfDay();

        $dailyCount = Transaction::where('merchant_id', $transaction->merchant_id)
            ->where('currency', $transaction->currency)
            ->where('created_at', '>=', $today)
            ->count();

        $dailySum = Transaction::where('merchant_id', $transaction->merchant_id)
            ->where('currency', $transaction->currency)
            ->where('created_at', '>=', $today)
            ->sum('amount');

        if ($dailyCount >= $limit->daily_count) {
            return [
                'status' => 'review',
                'reason' => 'Daily transaction count limit reached.'
            ];
        }

        if ($dailySum + $transaction->amount > $limit->daily_sum) {
            return [
                'status' => 'review',
                'reason' => 'Daily transaction sum limit exceeded.'
            ];
        }

        return ['status' => 'ok'];
    }

    /**
     * Vérifie si email/phone/client est blacklisté
     */
    public function blacklistCheck(Transaction $transaction): array
    {
        // TODO: créer une table blacklist_entries
        // Pour l’instant on simule
        $blacklistedPhones = ['22900000000'];
        $blacklistedEmails = ['fraude@fake.com'];

        if ($transaction->customer && in_array($transaction->customer->phone, $blacklistedPhones)) {
            return [
                'status' => 'blocked',
                'reason' => 'Customer phone is blacklisted.'
            ];
        }

        if ($transaction->customer && in_array($transaction->customer->email, $blacklistedEmails)) {
            return [
                'status' => 'blocked',
                'reason' => 'Customer email is blacklisted.'
            ];
        }

        return ['status' => 'ok'];
    }

    /**
     * Calcul d'un score de risque simple
     */
    public function riskScore(Transaction $transaction): array
    {
        $score = 0;

        // Montant élevé
        if ($transaction->amount > 200000) { // configurable
            $score += 30;
        }

        // Client créé aujourd'hui
        if ($transaction->customer && $transaction->customer->created_at->isToday()) {
            $score += 20;
        }

        // Plusieurs transactions rapides
        $recent = Transaction::where('merchant_id', $transaction->merchant_id)
            ->where('customer_id', $transaction->customer_id)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        if ($recent >= 3) {
            $score += 30;
        }

        if ($score >= 60) {
            return [
                'status' => 'review',
                'reason' => 'Suspicious activity, risk score too high.'
            ];
        }

        return ['status' => 'ok'];
    }

    /**
     * Vérification complète anti-fraude
     */
    public function evaluate(Transaction $transaction): array
    {
        // 1. Velocity
        $velocity = $this->velocityCheck($transaction);
        if ($velocity['status'] !== 'ok') {
            return $velocity;
        }

        // 2. Blacklist
        $blacklist = $this->blacklistCheck($transaction);
        if ($blacklist['status'] !== 'ok') {
            return $blacklist;
        }

        // 3. Risk score
        return $this->riskScore($transaction);
    }
}