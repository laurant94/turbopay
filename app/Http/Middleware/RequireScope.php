<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireScope
{
    protected $scope;

    public function handle(Request $request, Closure $next, $scope)
    {
        /** @var \App\Models\ApiKey|null $apiKey */
        $apiKey = $request->attributes->get('api_key');

        if (! $apiKey) {
            return response()->json(['success' => false, 'message' => 'No API key attached'], 401);
        }

        if (! $apiKey->hasScope($scope)) {
            return response()->json(['success' => false, 'message' => 'Insufficient API key permissions'], 403);
        }


        return $next($request);
    }
}
