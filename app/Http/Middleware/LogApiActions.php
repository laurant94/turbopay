<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;
use App\Models\ApiKey;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class LogApiActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $apiKey = $request->attributes->get('api_key');

        if ($apiKey instanceof ApiKey) {
            $this->logApiAction($request, $response, $apiKey);
        }

        return $response;
    }

    protected function logApiAction(Request $request, Response $response, ApiKey $apiKey): void
    {
        $route = $request->route();
        $routeName = $route ? $route->getName() : null;
        $event = 'api.' . ($routeName ?? $request->method() . '.' . $request->path());

        $auditableType = null;
        $auditableId = null;

        if ($route) {
            foreach ($route->parameters() as $paramName => $paramValue) {
                if (is_object($paramValue) && method_exists($paramValue, 'getKey')) {
                    $auditableType = $paramValue::class;
                    $auditableId = $paramValue->getKey();
                    break;
                }
            }
        }

        AuditLog::create([
            'user_type' => ApiKey::class,
            'user_id' => $apiKey->id,
            'merchant_id' => $apiKey->merchant_id,
            'event' => $event,
            'method' => $request->method(), // New: Request method
            'path' => $request->path(), // New: Full request path
            'response_status' => $response->getStatusCode(), // New: Response status
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => [],
            'new_values' => $request->except(['secret', 'password', 'api_key', 'token']),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}