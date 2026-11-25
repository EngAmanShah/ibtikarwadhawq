<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** يوجّه إلى معالج التثبيت إذا لم يتم التثبيت بعد، ويغلق المعالج بعد التثبيت */
class EnsureInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        $installed = cms_installed();
        $isInstaller = $request->is('install') || $request->is('install/*');

        if (! $installed && ! $isInstaller) {
            return redirect()->route('install.welcome');
        }

        if ($installed && $isInstaller && ! $request->is('install/done')) {
            return redirect('/');
        }

        return $next($request);
    }
}
