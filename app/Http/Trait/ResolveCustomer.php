<?php

namespace App\Http\Trait;

use App\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

trait ResolveCustomer
{
    protected function resolveCustomer(array|int|null $customerData): Customer{
         /* ---------- 1. Normalisation en array ---------- */
        if (is_int($customerData)) {
            $customerData = ['id' => $customerData];
        }

        if (!is_array($customerData) || empty($customerData)) {
            throw ValidationException::withMessages([
                'customer' => 'Un id ou un email est requis pour identifier le client.',
            ]);
        }

        /* ---------- 2. Recherche ---------- */
        $query = Customer::query();

        if (!empty($customerData['id'])) {
            $customer = $query->findOrFail($customerData['id']);
            if(isset($customerData['phone'])){
                $customer->update([
                    'phone'=> $customerData['phone']
                ]);
            }
            return $customer;
        }

        if (!empty($customerData['email'])) {
            return $query->firstOrCreate(
                ['email' => $customerData['email']],   // critères de recherche
                Arr::only($customerData, ['firstname', 'lastname', 'phone']) // valeurs si création
            );
        }

        /* ---------- 3. Aucun critère ---------- */
        throw ValidationException::withMessages([
            'customer' => 'Aucun identifiant (id ou email) fourni.',
        ]);
    }
}
