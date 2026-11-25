<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $days = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->toDateString());
        $hits = DB::table('visits')->where('day', '>=', $days->first())->groupBy('day')->select('day', DB::raw('SUM(hits) as total'))->pluck('total', 'day');
        $chart = $days->map(fn ($d) => ['day' => $d, 'v' => (int) ($hits[$d] ?? 0)]);

        $week = $chart->slice(7)->sum('v');
        $prevWeek = $chart->slice(0, 7)->sum('v');
        $topPages = DB::table('visits')->where('day', '>=', now()->subDays(30)->toDateString())->groupBy('path')
            ->select('path', DB::raw('SUM(hits) as total'))->orderByDesc('total')->limit(5)->get();

        // فحص جودة الموقع — قائمة مهام بسيطة للعميل
        $checks = [];
        $add = function ($ok, $label, $url) use (&$checks) { $checks[] = compact('ok', 'label', 'url'); };
        $add((bool) setting('logo_icon') && (bool) setting('favicon'), 'الشعار وأيقونة المتصفح مضافة', route('admin.pages.edit', 'identity'));
        $add(Models\Branch::active()->whereNotNull('phone')->exists(), 'أرقام التواصل مضافة', route('admin.branches.index'));
        $add((bool) setting('whatsapp_main'), 'رقم الواتساب الرئيسي مضاف', route('admin.pages.edit', 'contact_info'));
        $seoOk = collect(['home', 'about', 'services', 'documents', 'contact'])->every(fn ($p) => mb_strlen((string) setting("seo_{$p}_description")) >= 70);
        $add($seoOk, 'كل الصفحات لها وصف جيد في جوجل', route('admin.pages.edit', 'seo'));
        $missing = Models\Service::active()->get()->filter(fn ($s) => ! $s->card_image || ! $s->meta_description || $s->items()->count() === 0)->count();
        $add($missing === 0, $missing ? "$missing خدمة تحتاج صورة أو وصف أو أقسام" : 'كل الخدمات مكتملة', route('admin.services.index'));
        $expired = Models\Document::active()->whereNotNull('expires_at')->where('expires_at', '<=', now()->addDays(30))->count();
        $add($expired === 0, $expired ? "$expired وثيقة منتهية أو تنتهي قريبًا" : 'الوثائق الرسمية سارية', route('admin.documents.index'));
        $add(Models\Lead::where('status', 'new')->where('created_at', '<', now()->subDays(2))->doesntExist(), 'لا توجد طلبات متأخرة بدون رد', route('admin.leads.index'));
        $score = (int) round(collect($checks)->where('ok', true)->count() / count($checks) * 100);

        return view('admin.dashboard', [
            'chart' => $chart, 'week' => $week, 'prevWeek' => $prevWeek, 'topPages' => $topPages,
            'today' => (int) ($hits[now()->toDateString()] ?? 0),
            'leadsNew' => Models\Lead::where('status', 'new')->count(),
            'leadsMonth' => Models\Lead::where('created_at', '>=', now()->startOfMonth())->count(),
            'latestLeads' => Models\Lead::latest()->limit(5)->get(),
            'counts' => [
                'services' => Models\Service::count(), 'clients' => Models\Client::count(),
                'documents' => Models\Document::count(), 'slides' => Models\Slide::count(),
            ],
            'checks' => $checks, 'score' => $score,
            'activity' => Models\Activity::with('user')->latest()->limit(6)->get(),
        ]);
    }
}
