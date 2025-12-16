<?php

namespace App\Http\Controllers\User;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiKeyController extends Controller
{
    public function index(){
        $merchant = $this->getMerchant();
        $keys = $merchant->apiKeys()->where('active', true)->get();

        $headers = [
            [
                'label'=> "Nom",
                'key'=> 'name'
            ],
            [
                'label'=> "Clé",
                'key'=> 'key_prefix'
            ],
            [
                'label'=> "Dernière utilisation",
                'key'=> 'last_used_at'
            ],
            // [
            //     'label'=> "Actions",
            //     'key'=> 'actions'
            // ],
        ];

        return inertia('ApiKey/Index', [
            'items'=> $keys,
            'headers'=> $headers,
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
