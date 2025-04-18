<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use PHPOpenSourceSaver\JWTAuth\JWT;

class JwtAuthMiddleware
{
    protected $jwt;

    public function __construct(JWT $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->jwt->checkOrFail();
        return $next($request);
    }
}
