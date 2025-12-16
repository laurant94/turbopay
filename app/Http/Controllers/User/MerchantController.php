<?php

namespace App\Http\Controllers\User;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Services\ApiKeyService;
use App\Http\Enums\ApiScopeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class MerchantController extends Controller
{
    public function index(Request $request){
        $merchants = $request->user()->merchants;
    }

    public function store(Request $request){
        $data = $request->validate([
            'name'=> "required|string|max:255",
            'country'=> "nullable|string",
        ]);

        $data['country'] = "BJ";

        $merchant = $request->user()->merchants()->create($data);


        // Api keys creation
        ApiKeyService::generate(
            $merchant,
            'Clé publique',
            'public',
            ApiScopeEnum::publicScopes()
        );

        // Générer aussi une clé privée (secret)
        ApiKeyService::generate(
            $merchant,
            'Clé privée',
            'secret',
            ApiScopeEnum::privateScopes()
        );


        $request->session()->put("merchant", $merchant->id);

        return back()->with("success", "Marchant créé");

    }

    public function switch(Request $request, Merchant $merchant)
    {
       
        if ($merchant) {
            /** @var \App\Models\User $user */
            $request->session()->put('merchant', $merchant->id);
        }

        // return redirect()->back();
        return to_route('dashboard');
    }
}
