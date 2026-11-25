<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Lead;
use App\Models\Service;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::defaultView('admin.partials.pagination');

        // بيانات مشتركة لكل صفحات الموقع (القائمة، الفوتر، الفروع)
        View::composer('site.*', function ($view) {
            static $shared = null;
            if ($shared === null) {
                try {
                    $shared = [
                        'menuServices' => Service::active()->where('in_menu', true)->ordered()->get(),
                        'branches' => Branch::active()->ordered()->get(),
                    ];
                } catch (\Throwable $e) {
                    $shared = ['menuServices' => collect(), 'branches' => collect()];
                }
            }
            $view->with($shared);
        });

        // عدد الطلبات الجديدة في القائمة الجانبية
        View::composer('admin.layouts.app', function ($view) {
            try {
                $view->with('newLeads', Lead::where('status', 'new')->count());
            } catch (\Throwable $e) {
                $view->with('newLeads', 0);
            }
        });
    }
}
