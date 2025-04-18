<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class BrowserHistory
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

        $agent = new Agent($request->server->all());
        $browser = $agent->browser();
        $platform = $agent->platform();
        $deviceType = $agent->deviceType();
        $isRobot = $agent->isRobot();
        $robotName = $isRobot ? $agent->robot() : null;
        $clientIp = request()->getClientIp();
        $link = $request->fullUrl();

        \App\Models\Base\BrowserHistory::create([
            'platform' => $platform,
            'device_type' => $deviceType,
            'is_robot' => $isRobot,
            'robot_name' => $robotName,
            'sourceip' => $clientIp,
            'browser' => $browser,
            'link' => $link
        ]);

        return $next($request);
    }
}
