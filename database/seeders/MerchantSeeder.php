<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\User;
use App\Models\ApiKey;
use App\Models\Merchant;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Http\Enums\ApiScopeEnum;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $user = User::create([
            'name'=>'Test User',
            'email'=>'merchant@test.com',
            'password'=>Hash::make('password123'),
            'role'=>'merchant'
        ]);

        $merchant = Merchant::create([
            'id'=>Str::uuid(),
            'user_id'=>$user->id,
            'name'=>'Test Merchant',
            'country'=>'BJ',
            'status'=>'verified',
            'activated_at'=>now()
        ]);

        $result = \App\Services\ApiKeyService::generate(
            $merchant,
            'Sandbox public key',
            'public',
            ApiScopeEnum::publicScopes()
        );

        // Afficher la clé brute UNE FOIS
        $this->command->info("Clé publique (à copier maintenant) : {$result['key']}");

        // Générer aussi une clé privée (secret)
        $result2 = \App\Services\ApiKeyService::generate(
            $merchant,
            'Sandbox secret key',
            'secret',
            ApiScopeEnum::privateScopes()
        );
        $this->command->info("Clé privée (à copier maintenant) : {$result2['key']}");

        $merchantId = $merchant->id;


        $fees = [
            // -------------------- SANDBOX --------------------
            [
                'merchant_id' => $merchantId,
                'currency'    => 'XOF',
                'min_amount'  => 0,
                'max_amount'  => 50000,
                'percent'     => 1.5,
                'fixed'       => 100,
                'scope'       => 'sandbox',
            ],
            [
                'merchant_id' => $merchantId,
                'currency'    => 'XOF',
                'min_amount'  => 50001,
                'max_amount'  => 500000,
                'percent'     => 1.2,
                'fixed'       => 200,
                'scope'       => 'sandbox',
            ],
            [
                'merchant_id' => $merchantId,
                'currency'    => 'XOF',
                'min_amount'  => 500001,
                'max_amount'  => 999999999,
                'percent'     => 1.0,
                'fixed'       => 500,
                'scope'       => 'sandbox',
            ],
        ];

        foreach ($fees as $fee) {
            Fee::create($fee);
        }
    }
}
