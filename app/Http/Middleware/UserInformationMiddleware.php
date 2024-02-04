<?php

// app/Http/Middleware/UserInformationMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Torann\GeoIP\Facades\GeoIP;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class UserInformationMiddleware
{
    public function handle($request, Closure $next)
    {
        $userAgent = $request->header('User-Agent');
        $agent = new Agent();
        $ipAddress = $request->ip();

        // Get location information
        $location = Location::get($ipAddress);

        // Store information in session, you can modify this to store in a database
        session([
            'user_information' => [
                'ip_address' => $request->ip(),
                'country' => $location,
                'city' => $location,
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'device' => $agent->device(),
                'platform' => $agent->platform(),
                'is_mobile' => $agent->isMobile(),
                'is_tablet' => $agent->isTablet(),
                'is_desktop' => $agent->isDesktop(),
                'is_robot' => $agent->isRobot(),
                'languages' => $agent->languages(),
                'referer' => $request->header('referer'),
                'user_agent' => $userAgent,
            ]
        ]);

        return $next($request);
    }
}
