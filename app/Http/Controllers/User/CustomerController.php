<?php

namespace App\Http\Controllers\User;

use App\Models\Customer;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index(Request $request){

        $merchant = $this->getMerchant();
        $items = $merchant->customers()->paginate($request->perPage ?? env("perPage", 15));

        $headers = [
            [
                'label'=> "Client",
                'key'=> 'quick_details'
            ],
            [
                'label'=> "Date de creation",
                'key'=> 'created_at'
            ],
            [
                'label'=> "Actions",
                'key'=> 'actions'
            ],
        ];
        // dd($items);

        return inertia('Customer/Index', [
            'items'=> $items,
            'headers'=> $headers,
        ]);

    }

    public function store(Request $request){
        $datas = $request->validate([
            'firstname'=> "required|string|max:255",
            'lastname'=> "required|string|max:255",
            'email'=> "required|string|max:255|email|unique:customers,email",
            'phone'=> "required|string|max:255|unique:customers,phone",
        ]);

        $merchant = $this->getMerchant();
        $customer = $merchant->customers()->create($datas);

        return back()->with('success', "Client créé");

    }


    public function show(Request $request, Customer $customer){
        $merchant = $this->getMerchant();

        if(!$merchant->customers()->where('id', $customer->id)->exists() ){
            return abort(404, );
        }

        return inertia("Customer/Show", [
            "item" => $customer
        ]);
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
