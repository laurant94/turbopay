<?php

namespace App\Http\Controllers\User;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index(Request $request){
        $merchant = $this->getMerchant();
        $transactions = $merchant->transactions()
            ->latest()
            ->with(['customer'])
            ->paginate($request->perPage ?? env("perPage", 15));

        $customers = $merchant->customers;
        $currencies = Currency::all(['name', 'code']);

        $headers = [
            [
                'label'=> "Montant",
                'key'=> 'amount'
            ],
            [
                'label'=> "Client",
                'key'=> 'customer_id'
            ],
            [
                'label'=> "Status",
                'key'=> 'status'
            ],
            [
                'label'=> "Creation",
                'key'=> 'created_at'
            ],
            [
                'label'=> "Actions",
                'key'=> 'actions'
            ],
        ];

        return inertia('Transactions/Index', [
            'items'=> $transactions,
            'headers'=> $headers,
            'customers'=> $customers,
            'currencies' => $currencies,
        ]);
    }


    public function store(Request $request){
        $data = $request->validate([
            'customer_id'=> "required|integer|exists:customers,id",
            'amount' => "required|integer|min:100",
            'currency'=> "required|exists:currencies,code",
            'callback_url'=> "nullable|string",
            'description'=> "required|string",
        ]);

        $merchant = $this->getMerchant();
        $data['reference'] = Str::orderedUuid();
        $transaction = $merchant->transactions()->create($data);

        return back()->with("success", "Transaction créé");

    }

    public function show(Transaction $transaction){
        if($transaction->merchant_id != session('merchant')){
            return abort(404, "Transaction introuvable");
        }

        return inertia('Transactions/Show', [
            'item'=> $transaction->load(['customer', 'merchant'])
        ]);
    }

    public function generatePaymentToken(Request $request, Transaction $transaction){
        if($transaction->merchant_id != session('merchant')){
            return abort(404, "Transaction introuvable");
        }
        $service = app(TransactionService::class);
        $data = $service->generatePaymentToken($transaction);

        return back()->with("datas", $data);
    }








    protected function getMerchant(): Merchant{
        $merchantId = session('merchant');
        if(!$merchantId){
            return abort(404, "Aucun marchant trouvé");
        }

        $merchant = Merchant::findOrFail($merchantId);

        return $merchant;
    }

}
