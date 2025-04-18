<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ViewCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $client_ip = $request->ip();

        cache()->remember("isViewCount_$client_ip", now()->endOfDay(), function() use ($client_ip){
            \Modules\System\Models\Viewcount::create([
                'sourceip' => $client_ip
            ]);

            return 1;
        });

        return $next($request);
    }
}
