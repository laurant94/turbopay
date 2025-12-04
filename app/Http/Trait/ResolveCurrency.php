<?php

namespace App\Http\Trait;

use App\Models\Currency;
use Illuminate\Validation\ValidationException;

trait ResolveCurrency
{
    protected function resolveCurrency(int|array $currency): Currency{

        if (is_int($currency)) {
            $currency = ['id' => $currency];
        }

        if (!is_array($currency) || empty($currency)) {
            throw ValidationException::withMessages([
                'currency' => 'Invalide currency',
            ]);
        }

        $query = Currency::query();

        if (!empty($currency['id'])) {
            return $query->findOrFail($currency['id']);
        }
        elseif (!empty($currency['iso'])) {
            return $query->where('code', $currency['iso'])->first();
        }

        /* ---------- 3. Aucun critère ---------- */
        throw ValidationException::withMessages([
            'customer' => 'Aucun identifiant (id ou email) fourni.',
        ]);

    }
}
