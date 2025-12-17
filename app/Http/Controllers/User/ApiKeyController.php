<?php

namespace App\Http\Controllers\User;

use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Services\ApiKeyService;
use App\Http\Enums\ApiScopeEnum;
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
                'label'=> "Création",
                'key'=> 'created_at'
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

    public function store(Request $request){
        $merchant = $this->getMerchant();
        $merchant->apiKeys()->update([
            'active'=> false,
        ]);

         // Api keys creation
        $public = ApiKeyService::generate(
            $merchant,
            'Clé publique',
            'public',
            ApiScopeEnum::publicScopes()
        );

        // Générer aussi une clé privée (secret)
        $private = ApiKeyService::generate(
            $merchant,
            'Clé privée',
            'secret',
            ApiScopeEnum::privateScopes()
        );

        return to_route("user.apiKeys.index")->with("datas", [
            'public'=> $public,
            'private'=> $private,
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
