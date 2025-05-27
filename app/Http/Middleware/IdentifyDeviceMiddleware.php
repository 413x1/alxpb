<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyDeviceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Exclude device auth routes from this check
        if ($this->shouldPassThrough($request)) {
            return $next($request);
        }

        // Check for active device session
        if (!$this->hasActiveDeviceSession()) {
            return redirect()->route('device.login')->withErrors([
                'device' => 'Please authenticate your device first'
            ]);
        }

        if($this->isDeviceLoginRoute($request)) {
            return redirect()->route('home');
        }

        return $next($request);
    }

    /**
     * Determine if the request should pass through the middleware.
     */
    protected function shouldPassThrough(Request $request): bool
    {
        $routes = [
            'device.auth',
            'device.logout',
        ];

        return $request->routeIs($routes);
    }

    protected function isDeviceLoginRoute(Request $request) : bool {
        return $request->routeIs(['device.login']);
    }

    /**
     * Check if active device session exists.
     */
    protected function hasActiveDeviceSession(): bool
    {
        return session()->has('active_device') &&
            session()->has('active_device_id');
    }
}
