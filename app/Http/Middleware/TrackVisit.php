<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/** عدّاد زيارات خفيف (بدون كوكيز وبدون بيانات شخصية) يظهر في الرئيسية للوحة التحكم */
class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $ua = strtolower((string) $request->userAgent());
            $isBot = $ua === '' || preg_match('/bot|crawl|spider|slurp|facebookexternalhit|preview|monitor|curl|wget|python|lighthouse/', $ua);

            if ($request->isMethod('GET') && ! $isBot && $response->getStatusCode() === 200 && ! auth()->check()) {
                $path = '/' . ltrim($request->path(), '/');
                $day = now()->toDateString();
                $updated = DB::table('visits')->where('day', $day)->where('path', $path)->increment('hits');
                if (! $updated) {
                    Visit::insertOrIgnore(['day' => $day, 'path' => mb_substr($path, 0, 190), 'hits' => 1]);
                }
            }
        } catch (\Throwable $e) {
            // لا نوقف الموقع بسبب العداد
        }

        return $response;
    }
}
