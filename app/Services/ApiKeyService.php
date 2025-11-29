<?php
namespace App\Services;

use App\Models\ApiKey;
use App\Models\Merchant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ApiKeyService{
    /**
     * Génère une clé (prefix + secret), stocke key_prefix + key_hash.
     * Retourne ['key' => <clé brute à afficher UNE FOIS>, 'api_key' => ApiKey instance]
     */
    public static function generate(Merchant $merchant, string $name = 'Key', 
        string $type = 'public', array $scopes = []): array
    {
        // préfixe visible (utilisé pour lookup)
        $prefix = $type === 'public' ? 'pk_' : 'sk_';
        $random = Str::random(40);
        $fullKey = $prefix . $random;

        // stock : prefix index (p.ex. premiers 24 chars) et hash complet
        $keyPrefix = substr($fullKey, 0, env("APIKEY_PREFIX_LENGTH"));
        $keyHash = Hash::make($fullKey);

        $apiKey = ApiKey::create([
            'merchant_id' => $merchant->id,
            'name' => $name,
            'key_prefix' => $keyPrefix,
            'key_hash' => $keyHash,
            'type' => $type,
            'scopes' => $scopes,
            'active' => true
        ]);

        // retourner la clé brute (à afficher une fois) + l'instance
        return [
            'key' => $fullKey,
            'api_key' => $apiKey
        ];
    }

    public static function revoke(ApiKey $apiKey, ?string $reason = null): ApiKey
    {
        $apiKey->revoke($reason);
        return $apiKey->refresh();
    }
}