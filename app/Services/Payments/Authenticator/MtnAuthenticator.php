<?php

namespace App\Services\Payments\Authenticator;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MtnAuthenticator{

    
    public static function auth(){
        $response = Http::withBasicAuth(env('MTN_REFERENCE_ID'), env('MTN_API_KEY'))
        ->withHeaders([
            "Ocp-Apim-Subscription-Key"=> env('MTN_COLLECTION_KEY'),
            "Content-Type" => "application/json"
        ])
        ->post(env('MTN_BASE_URL') . '/collection/token/');

        if($response->status() === 200){
            $token = $response->json("access_token");
        }
    }
}