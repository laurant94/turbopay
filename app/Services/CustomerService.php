<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerService
{
    /**
     * Lister les customers du merchant avec filtres simples.
     */
    public function list($merchant, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Customer::where('merchant_id', $merchant->id);

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($qdb) use ($q) {
                $qdb->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['email'])) {
            $query->where('email', $filters['email']);
        }

        if (!empty($filters['phone'])) {
            $query->where('phone', $filters['phone']);
        }

        // tri et pagination
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    /**
     * Créer un customer pour le merchant.
     */
    public function create($merchant, array $data): Customer
    {
        $data['merchant_id'] = $merchant->id;
        return Customer::create($data);
    }

    /**
     * Récupérer un client du merchant (ou 404).
     */
    public function findOrFail($merchant, int $id): Customer
    {
        return Customer::where('merchant_id', $merchant->id)->findOrFail($id);
    }

    /**
     * Mettre à jour.
     */
    public function update($merchant, Customer $customer, array $data): Customer
    {
        // sécurité : s'assurer que le customer appartient bien au merchant
        if ($customer->merchant_id !== $merchant->id) {
            abort(403, 'Forbidden');
        }

        $customer->update($data);
        return $customer->refresh();
    }

    /**
     * Supprimer (soft delete possible si tu ajoutes softDeletes).
     */
    public function delete($merchant, Customer $customer): bool
    {
        if ($customer->merchant_id !== $merchant->id) {
            abort(403, 'Forbidden');
        }

        return $customer->delete();
    }
}
