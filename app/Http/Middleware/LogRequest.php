<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\LogUtil;

class LogRequest
{
    use LogUtil;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->log('REQUEST '.json_encode([
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'header' => $request->header(),
            'body' => $request->all(),
        ]));

        return $next($request);
    }
}
