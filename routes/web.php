<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| معالج التثبيت
|--------------------------------------------------------------------------
*/
Route::prefix('install')->name('install.')->controller(InstallController::class)->group(function () {
    Route::get('/', 'welcome')->name('welcome');
    Route::get('/requirements', 'requirements')->name('requirements');
    Route::get('/database', 'database')->name('database');
    Route::post('/database/test', 'testDatabase')->name('database.test');
    Route::post('/database', 'saveDatabase')->name('database.save');
    Route::get('/account', 'account')->name('account');
    Route::post('/account', 'finish')->name('finish');
    Route::get('/done', 'done')->name('done');
});

/*
|--------------------------------------------------------------------------
| الموقع العام
|--------------------------------------------------------------------------
*/
Route::middleware('track')->controller(SiteController::class)->name('site.')->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services');
    Route::get('/services/{slug}', 'service')->name('service');
    Route::get('/documents', 'documents')->name('documents');
    Route::get('/contact', 'contact')->name('contact');
});
Route::post('/contact', [SiteController::class, 'storeLead'])->middleware('throttle:6,1')->name('site.lead');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('site.sitemap');
Route::get('/llms.txt', [SiteController::class, 'llms'])->name('site.llms');
Route::get('/index.html', fn () => redirect('/', 301));

/*
|--------------------------------------------------------------------------
| لوحة التحكم
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [Admin\AuthController::class, 'show'])->name('login');
        Route::post('/login', [Admin\AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // صفحات المحتوى والإعدادات (مبنية تلقائيًا من config/cms.php)
        Route::get('/pages/{page}', [Admin\PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [Admin\PageController::class, 'update'])->name('pages.update');

        // ترتيب وإظهار/إخفاء العناصر (AJAX)
        Route::post('/reorder/{type}', [Admin\ReorderController::class, 'reorder'])->name('reorder');
        Route::post('/toggle/{type}/{id}', [Admin\ReorderController::class, 'toggle'])->name('toggle');

        // الخدمات وأقسامها
        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::get('services/{service}/items/create', [Admin\ServiceItemController::class, 'create'])->name('services.items.create');
        Route::post('services/{service}/items', [Admin\ServiceItemController::class, 'store'])->name('services.items.store');
        Route::get('service-items/{item}/edit', [Admin\ServiceItemController::class, 'edit'])->name('services.items.edit');
        Route::put('service-items/{item}', [Admin\ServiceItemController::class, 'update'])->name('services.items.update');
        Route::delete('service-items/{item}', [Admin\ServiceItemController::class, 'destroy'])->name('services.items.destroy');

        // القوائم البسيطة
        Route::resource('slides', Admin\SlideController::class)->except('show');
        Route::resource('clients', Admin\ClientController::class)->except('show');
        Route::resource('documents', Admin\DocumentController::class)->except('show');
        Route::resource('branches', Admin\BranchController::class)->except('show');

        Route::get('blocks/{group}', [Admin\BlockController::class, 'index'])->name('blocks.index');
        Route::get('blocks/{group}/create', [Admin\BlockController::class, 'create'])->name('blocks.create');
        Route::post('blocks/{group}', [Admin\BlockController::class, 'store'])->name('blocks.store');
        Route::get('blocks/{group}/{block}/edit', [Admin\BlockController::class, 'edit'])->name('blocks.edit');
        Route::put('blocks/{group}/{block}', [Admin\BlockController::class, 'update'])->name('blocks.update');
        Route::delete('blocks/{group}/{block}', [Admin\BlockController::class, 'destroy'])->name('blocks.destroy');

        // الطلبات
        Route::get('leads', [Admin\LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/export', [Admin\LeadController::class, 'export'])->name('leads.export');
        Route::get('leads/{lead}', [Admin\LeadController::class, 'show'])->name('leads.show');
        Route::put('leads/{lead}', [Admin\LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [Admin\LeadController::class, 'destroy'])->name('leads.destroy');

        // الحساب
        Route::get('account', [Admin\AccountController::class, 'edit'])->name('account');
        Route::put('account', [Admin\AccountController::class, 'update'])->name('account.update');
    });
});
