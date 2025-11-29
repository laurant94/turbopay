<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = $request->header('Authorization') ?? $request->query('api_key');

        if (! $auth || ! preg_match('/Bearer\s+(.+)/i', $auth, $m)) {
            return response()->json(['success' => false, 'message' => 'Missing API key'], 401);
        }


        $providedKey = trim($m[1]);

        // extract prefix for lookup (same length used when generating)
        $prefixLength = env("APIKEY_PREFIX_LENGTH");
        $providedPrefix = substr($providedKey, 0, $prefixLength);

        // chercher les candidats par préfixe (indexé)
        $candidates = ApiKey::where('key_prefix', $providedPrefix)
            ->where('active', true)
            ->get();

        if ($candidates->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Invalid API key'], 401);
        }

        // vérifier chaque candidat via Hash::check
        $matched = null;
        foreach ($candidates as $candidate) {
            if ($candidate->checkSecret($providedKey)) {
                $matched = $candidate;
                break;
            }
        }

        if (! $matched) {
            return response()->json(['success' => false, 'message' => 'Invalid API key'], 401);
        }

        // check revoked/active
        if (! $matched->isActive()) {
            return response()->json(['success' => false, 'message' => 'API key revoked'], 403);
        }

        // attacher merchant à la requête ; request()->user() renverra Merchant si besoin.
        $merchant = $matched->merchant;
        $request->setUserResolver(function () use ($merchant) {
            return $merchant;
        });

        // attacher la clé et ses scopes à l'objet request
        $request->attributes->set('api_key', $matched);
        $matched->markUsed();

        return $next($request);
    }
}
