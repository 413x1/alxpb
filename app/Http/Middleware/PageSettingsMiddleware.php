<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PageSettingsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle($request, Closure $next): Response
    {
        $path = trim($request->path(), '/');
        $segments = explode('/', $path);

        // Remove empty segments
        $segments = array_filter($segments);

        // Generate title from last non-empty segment
        $title = $this->generateTitle($segments);

        // Generate breadcrumbs
        $breadcrumbs = $this->generateBreadcrumbs($request, $segments);

        // Share with all views
        view()->share([
            'pageTitle' => $title,
            'breadcrumbs' => $breadcrumbs
        ]);

        return $next($request);
    }

    protected function generateTitle(array $segments): string
    {
        if (empty($segments)) {
            return 'Home';
        }

        $lastSegment = last($segments);
        return Str::title(str_replace(['-', '_'], ' ', $lastSegment));
    }

    protected function generateBreadcrumbs($request, array $segments): array
    {
        $breadcrumbs = [];
        $url = '';

        // Always add Home as first breadcrumb with empty name (we'll use icon)
        $breadcrumbs[] = [
            'name' => '', // Empty name since we're using icon
            'url' => url('/'),
            'active' => empty($segments) // Active if we're on home page
        ];

        foreach ($segments as $segment) {
            $url .= '/' . $segment;
            $breadcrumbs[] = [
                'name' => Str::title(str_replace(['-', '_'], ' ', $segment)),
                'url' => url($url),
                'active' => $url === $request->getRequestUri()
            ];
        }

        return $breadcrumbs;
    }
}
