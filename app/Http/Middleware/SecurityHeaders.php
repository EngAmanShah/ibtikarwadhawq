<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** ترويسات أمان أساسية تحسّن تقييم الموقع لدى المتصفحات ومحركات البحث */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        // توحيد النطاق: إزالة www حتى لا تظهر نسختان من نفس الصفحة في محركات البحث
        $canonicalHost = (string) parse_url(site_base(), PHP_URL_HOST);
        if ($canonicalHost !== '' && str_starts_with($request->getHost(), 'www.')
            && substr($request->getHost(), 4) === $canonicalHost) {
            return redirect()->away(preg_replace('#^(https?)://www\.#i', '$1://', $request->fullUrl()), 301);
        }

        $response = $next($request);

        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Cross-Origin-Opener-Policy' => 'same-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=()',
        ];

        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000';
        }

        // لوحة التحكم ومعالج التثبيت لا يُفترض ظهورهما في محركات البحث
        if ($request->is('admin', 'admin/*', 'install', 'install/*')) {
            $headers['X-Robots-Tag'] = 'noindex, nofollow';
        }

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) $response->headers->set($name, $value);
        }

        return $response;
    }
}
