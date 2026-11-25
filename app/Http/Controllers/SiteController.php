<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Client;
use App\Models\Document;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Slide;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        return view('site.home', [
            'seo' => $this->seo('home'),
            'services' => Service::active()->ordered()->get(),
            'slides' => Slide::active()->ordered()->get(),
            'clients' => Client::active()->ordered()->get(),
        ]);
    }

    public function about()
    {
        return view('site.about', [
            'seo' => $this->seo('about', 'من نحن', '/about'),
            'features' => Block::where('group', 'about_feature')->active()->ordered()->get(),
            'cards' => Block::where('group', 'about_card')->active()->ordered()->get(),
            'values' => Block::where('group', 'about_value')->active()->ordered()->get(),
        ]);
    }

    public function services()
    {
        return view('site.services', [
            'seo' => $this->seo('services', 'الخدمات', '/services'),
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function service(string $slug)
    {
        if (in_array($slug, ['cabinets', 'doors', 'renovation'])) {
            return redirect('/services/woodworks', 301);
        }

        $service = Service::active()->where('slug', $slug)->firstOrFail();
        $service->load(['items' => fn ($q) => $q->where('is_active', true)]);

        return view('site.service', [
            'service' => $service,
            'seo' => [
                'title' => $service->meta_title ?: $service->title . ' | ' . setting('brand_name'),
                'description' => $service->meta_description ?: $service->excerpt,
                'image' => $service->hero_image ?: $service->card_image,
                'path' => '/services/' . $service->slug,
                'crumbs' => [['الخدمات', '/services'], [$service->title, '/services/' . $service->slug]],
                'service' => $service,
            ],
        ]);
    }

    public function documents()
    {
        return view('site.documents', [
            'seo' => $this->seo('documents', 'الوثائق الرسمية', '/documents'),
            'documents' => Document::active()->ordered()->get(),
            'facts' => Block::where('group', 'company_fact')->active()->ordered()->get(),
        ]);
    }

    public function contact()
    {
        return view('site.contact', [
            'seo' => $this->seo('contact', 'تواصل معنا', '/contact'),
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function storeLead(Request $request)
    {
        // حقل مخفي لاصطياد برامج السبام
        if ($request->filled('website')) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:40',
            'service' => 'nullable|string|max:191',
            'space' => 'nullable|string|max:191',
            'message' => 'nullable|string|max:3000',
        ]);

        Lead::create($data + ['ip' => $request->ip(), 'status' => 'new']);

        return response()->json(['ok' => true, 'message' => setting('contact_form_success')]);
    }

    public function sitemap()
    {
        $base = rtrim(setting('site_url') ?: url('/'), '/');
        $urls = [['/', '1.0'], ['/about', '0.8'], ['/services', '0.9'], ['/documents', '0.7'], ['/contact', '0.8']];
        foreach (Service::active()->ordered()->get() as $s) {
            $urls[] = ['/services/' . $s->slug, '0.8'];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as [$path, $priority]) {
            $xml .= '  <url><loc>' . e($base . ($path === '/' ? '/' : $path)) . '</loc><lastmod>' . now()->toDateString() . '</lastmod><priority>' . $priority . '</priority></url>' . "\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    protected function seo(string $page, ?string $crumb = null, string $path = '/'): array
    {
        return [
            'title' => setting("seo_{$page}_title"),
            'description' => setting("seo_{$page}_description"),
            'image' => setting('og_image'),
            'path' => $path,
            'crumbs' => $crumb ? [[$crumb, $path]] : [],
        ];
    }
}
