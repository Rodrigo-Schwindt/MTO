<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttpsScheme
{
    public function handle(Request $request, Closure $next)
    {
        if (!app()->isLocal()) {
            $request->headers->set('X-Forwarded-Proto', 'https');
            $request->server->set('HTTP_X_FORWARDED_PROTO', 'https');
            $request->server->set('HTTPS', 'on');
        }

        return $next($request);
    }
}
