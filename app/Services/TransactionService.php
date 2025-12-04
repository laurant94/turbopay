<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Http\Trait\ResolveCurrency;
use App\Http\Trait\ResolveCustomer;
use App\Http\Enums\TransactionStatusEnum;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionService{

    use ResolveCustomer, ResolveCurrency;

    public function list($merchant, array $filters = [], int $perPage = 15): LengthAwarePaginator{
        $query = Transaction::where('merchant_id', $merchant->id);

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where(function ($qdb) use ($q) {
                $qdb->where('customer_id', 'like', "%{$q}%")
                    ->orWhere('amount', 'like', "%{$q}%")
                    ->orWhere('status', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('net_amount', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['description'])) {
            $query->where('description', $filters['description']);
        }

        // tri et pagination
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    public function create($merchant, array $data): Transaction
    {
        $data['merchant_id'] = $merchant->id;

        // if(array_key_exists('currency', $data)){
        //     if(isset($data['currency']['iso'])){
        //         $currency = Currency::where("code", $data['currency']['iso'])->first();
        //         if
        //     }
        // }
        $customer = array_key_exists("customer", $data) ? $this->resolveCustomer($data['customer']) : null;
        unset($data['customer']);
        if($customer){
            $data['customer_id'] = $customer->id;
        }

        $currency = array_key_exists("currency", $data) ? $this->resolveCurrency($data['currency']) : null;
        unset($data['currency']);
        if($currency){
            $data['currency'] = $currency->code;
        }


        $data['reference'] = Str::orderedUuid();
        $data['status'] = TransactionStatusEnum::Pending;

        

        return Transaction::create($data);
    }

    public function findOrFail($merchant, int|string $id): Transaction
    {
        return Transaction::where('merchant_id', $merchant->id)->findOrFail($id);
    }

    public function update($merchant, Transaction $transaction, array $data): Transaction
    {
        // sécurité : s'assurer que la transaction appartient bien au merchant
        if ($transaction->merchant_id !== $merchant->id) {
            abort(403, 'Forbidden');
        }

        $transaction->update($data);
        return $transaction->refresh();
    }

    public function delete($merchant, Transaction $transaction): bool
    {
        if ($transaction->merchant_id !== $merchant->id) {
            abort(403, 'Forbidden');
        }

        return $transaction->delete();
    }




    public function generatePaymentToken(Transaction $transaction): array
    {
        // token unique, signé
        $token = bin2hex(random_bytes(32));

        $transaction->payment_token = $token;
        $transaction->payment_token_expires_at = now()->addHours(24);
        $transaction->save();

        return [
            'token' => $token,
            'expires_at' => $transaction->payment_token_expires_at,
            'payment_url' => url("/momo-payment/{$token}"),
        ];
    }

}