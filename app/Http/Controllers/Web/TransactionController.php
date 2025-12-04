<?php

namespace App\Http\Controllers\Web;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessTransactionRequest;
use App\Models\Customer;
use App\Models\Merchant;
use App\Services\Payments\PaymentService;

class TransactionController extends Controller
{
    public function momoPayment(Request $request, string $token){

        $transaction = Transaction::where('payment_token', $token)
        ->with(['customer', 'merchant'])
        // ->where('payment_token_expires_at', '>', now())
        ->first();

        if(!$transaction){
            abort(404);
        }


        return inertia("Transaction/Process", [
            'transaction'=> $transaction
        ]);
        
    }

    public function process(ProcessTransactionRequest $request){
        $service = app(PaymentService::class);
        $data = $service->processMobilePayment(
            $request->token,
            $request->customer,
            $request->provider
        );

        return back()->with("datas", $data);
    }

    public function confirm(Request $request, string $id){
        $service = app(PaymentService::class);
        $data = $service->verifyPayment( $id);

        return back()->with("datas", $data);
    }


    public function updateCustomer(Request $request, Customer $customer){
        $data = $request->validate([
            'firstname'=> 'required|string',
            'lastname'=> 'required|string',
            'email' => 'required|unique:customers,email',
            'phone' => 'required|string'
        ]);

        $customer->update($data);

        return back();
    }

    public function createCustomer(Request $request){
        $data = $request->validate([
            'firstname'=> 'required|string',
            'lastname'=> 'required|string',
            'email' => 'required|unique:customers,email',
            'phone' => 'required|string',
            'merchant_id' => 'required|exists:merchants,id'
        ]);

        $customer = Customer::create($data);

        if($request->transaction_id){
            $transaction = Transaction::find($request->transaction_id);

            $transaction->customer_id = $customer->id;
            $transaction->save();
        }

        return back();
    }

}
