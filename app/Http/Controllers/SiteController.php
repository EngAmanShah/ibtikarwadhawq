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
        $services = Service::active()->ordered()->get();

        return view('site.services', [
            'seo' => $this->seo('services', 'الخدمات', '/services', ['services' => $services]),
            'services' => $services,
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
                'type' => 'ItemPage',
                'updated' => $service->updated_at?->toAtomString(),
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
        $base = site_base();
        $services = Service::active()->ordered()->get();

        $pages = [
            ['/', '1.0', 'site/home.blade.php'],
            ['/about', '0.8', 'site/about.blade.php'],
            ['/services', '0.9', 'site/services.blade.php'],
            ['/documents', '0.7', 'site/documents.blade.php'],
            ['/contact', '0.8', 'site/contact.blade.php'],
        ];

        $urls = [];
        foreach ($pages as [$path, $priority, $view]) {
            $urls[] = [
                'loc' => $base . $path,
                'lastmod' => $this->lastModified($view),
                'priority' => $priority,
                'image' => null,
                'title' => null,
            ];
        }

        foreach ($services as $service) {
            $urls[] = [
                'loc' => $base . '/services/' . $service->slug,
                'lastmod' => optional($service->updated_at)->toDateString() ?: $this->lastModified('site/service.blade.php'),
                'priority' => '0.8',
                'image' => absolute_media($service->hero_image ?: $service->card_image),
                'title' => $service->title,
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
        foreach ($urls as $url) {
            $xml .= '  <url><loc>' . e($url['loc']) . '</loc>'
                . '<lastmod>' . e($url['lastmod']) . '</lastmod>'
                . '<priority>' . e($url['priority']) . '</priority>';
            if ($url['image']) {
                $xml .= '<image:image><image:loc>' . e($url['image']) . '</image:loc>'
                    . ($url['title'] ? '<image:title>' . e($url['title']) . '</image:title>' : '')
                    . '</image:image>';
            }
            $xml .= '</url>' . "\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /** ملف llms.txt لتعريف وكلاء الذكاء الاصطناعي بمحتوى الموقع */
    public function llms()
    {
        $base = site_base();
        $brand = setting('brand_name');
        $lines = [
            '# ' . (setting('site_name') ?: $brand),
            '',
            '> ' . (setting('seo_home_description') ?: setting('footer_text')),
            '',
            '## نبذة',
            '',
            '- الشركة: ' . $brand . ' — ' . (setting('brand_suffix') ?: 'للمقاولات'),
            '- نطاق الخدمة: ' . implode('، ', \App\Models\Setting::list('seo_areas')),
            '- المدينة: ' . setting('seo_city') . ' — ' . setting('seo_region'),
            '- الهاتف: ' . setting('phone_main'),
            '- البريد: ' . setting('email'),
            '',
            '## الخدمات',
            '',
        ];

        foreach (Service::active()->ordered()->get() as $service) {
            $lines[] = '- [' . $service->title . '](' . $base . '/services/' . $service->slug . '): ' . trim((string) ($service->excerpt ?: $service->subtitle));
        }

        $lines = array_merge($lines, [
            '',
            '## الصفحات',
            '',
            '- [عن ' . $brand . '](' . $base . '/about)',
            '- [نطاق الأعمال](' . $base . '/services)',
            '- [الوثائق الرسمية](' . $base . '/documents)',
            '- [تواصل معنا](' . $base . '/contact)',
            '',
        ]);

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    protected function lastModified(string $view): string
    {
        $file = resource_path('views/' . $view);

        return is_file($file) ? date('Y-m-d', filemtime($file)) : now()->toDateString();
    }

    protected function seo(string $page, ?string $crumb = null, string $path = '/', array $extra = []): array
    {
        $types = [
            'home' => 'WebPage',
            'about' => 'AboutPage',
            'services' => 'CollectionPage',
            'documents' => 'CollectionPage',
            'contact' => 'ContactPage',
        ];

        return array_merge([
            'title' => setting("seo_{$page}_title"),
            'description' => setting("seo_{$page}_description"),
            'image' => setting('og_image'),
            'path' => $path,
            'crumbs' => $crumb ? [[$crumb, $path]] : [],
            'type' => $types[$page] ?? 'WebPage',
        ], $extra);
    }
}
