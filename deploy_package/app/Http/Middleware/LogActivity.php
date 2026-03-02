<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user()) {
            ActivityLog::query()->create([
                'user_id' => $request->user()->id,
                'action' => $request->method(),
                'module' => trim($request->route()?->getName() ?? 'unknown', '.'),
                'meta' => [
                    'url' => $request->fullUrl(),
                    'status' => $response->getStatusCode(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
