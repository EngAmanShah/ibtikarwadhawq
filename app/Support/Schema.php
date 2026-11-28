<?php

namespace App\Support;

use App\Models\Branch;
use App\Models\Setting;

/**
 * بيانات schema.org (JSON-LD) لصفحات الموقع.
 * تُبنى هنا في PHP لأن كلمة @context داخل ملفات Blade تُفسَّر كتوجيه وتُفسد الـ JSON.
 */
class Schema
{
    protected const CONTEXT = 'https://schema.org';

    protected const SOCIAL_KEYS = ['instagram', 'snapchat', 'tiktok', 'x', 'facebook', 'linkedin', 'youtube'];

    public static function graph(array $seo, $branches = null, $services = null): array
    {
        $base = site_base();
        $path = $seo['path'] ?? '/';
        $canonical = $base . ($path === '/' ? '/' : $path);
        $siteName = setting('site_name') ?: setting('brand_name');
        $brand = setting('brand_name') ?: $siteName;
        $pageImage = absolute_media($seo['image'] ?? setting('og_image'));
        $branches ??= self::loadBranches();
        $services ??= self::menuServices();

        $graph = [
            self::business($base, $siteName, $brand, $pageImage, $branches, $services),
            [
                '@type' => 'WebSite',
                '@id' => $base . '/#website',
                'url' => $base . '/',
                'name' => $siteName,
                'inLanguage' => 'ar-SA',
                'publisher' => ['@id' => $base . '/#business'],
            ],
            self::webPage($canonical, $seo, $pageImage, $base),
        ];

        if (! empty($seo['crumbs'])) {
            $crumbs = array_merge([['الرئيسية', '/']], $seo['crumbs']);
            $graph[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $canonical . '#breadcrumb',
                'itemListElement' => collect($crumbs)->values()->map(fn ($c, $i) => [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $c[0],
                    'item' => $base . ($c[1] === '/' ? '/' : $c[1]),
                ])->all(),
            ];
        }

        if (! empty($seo['service'])) {
            $graph[] = self::service($seo['service'], $canonical, $base, $pageImage);
        }

        if (! empty($seo['services'])) {
            $graph[] = [
                '@type' => 'ItemList',
                '@id' => $canonical . '#services',
                'name' => $seo['title'],
                'itemListElement' => collect($seo['services'])->values()->map(fn ($s, $i) => [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $s->title,
                    'url' => $base . '/services/' . $s->slug,
                ])->all(),
            ];
        }

        return ['@context' => self::CONTEXT, '@graph' => $graph];
    }

    protected static function business(string $base, string $siteName, string $brand, string $pageImage, $branches, $services): array
    {
        $logoPath = setting('logo_icon');
        $logo = [
            '@type' => 'ImageObject',
            '@id' => $base . '/#logo',
            'url' => absolute_media($logoPath),
            'contentUrl' => absolute_media($logoPath),
        ];
        $logoDims = media_dimensions($logoPath);
        if ($logoDims) {
            $logo['width'] = $logoDims['width'];
            $logo['height'] = $logoDims['height'];
        }

        $areas = array_values(array_filter(Setting::list('seo_areas')));
        $socials = [];
        foreach (self::SOCIAL_KEYS as $key) {
            $url = trim((string) setting('social_' . $key));
            if ($url !== '') $socials[] = $url;
        }

        $node = [
            '@type' => ['GeneralContractor', 'HomeAndConstructionBusiness'],
            '@id' => $base . '/#business',
            'name' => $siteName,
            'alternateName' => $brand,
            'url' => $base . '/',
            'description' => setting('seo_home_description') ?: setting('footer_text'),
            'logo' => $logo,
            'image' => $pageImage ? ['@type' => 'ImageObject', 'url' => $pageImage] : $logo,
            'email' => setting('email'),
            'telephone' => setting('phone_main'),
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'addressLocality' => setting('seo_city'),
                'addressRegion' => setting('seo_region'),
                'postalCode' => setting('seo_postal'),
                'addressCountry' => 'SA',
            ]),
            'areaServed' => array_map(fn ($city) => ['@type' => 'City', 'name' => $city], $areas),
            'sameAs' => $socials,
            'contactPoint' => $branches->map(fn ($b) => array_filter([
                '@type' => 'ContactPoint',
                'name' => $b->title,
                'telephone' => $b->phone_link,
                'contactType' => 'customer service',
                'areaServed' => 'SA',
                'availableLanguage' => 'Arabic',
            ]))->values()->all(),
            'knowsLanguage' => ['ar', 'en'],
            'currenciesAccepted' => 'SAR',
            'slogan' => setting('brand_suffix') ?: null,
        ];

        if ($services->isNotEmpty()) {
            $node['hasOfferCatalog'] = [
                '@type' => 'OfferCatalog',
                'name' => 'خدمات ' . $brand,
                'itemListElement' => $services->values()->map(fn ($s) => [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => $s->title,
                        'description' => $s->excerpt,
                        'url' => $base . '/services/' . $s->slug,
                    ],
                ])->all(),
            ];
        }

        return array_filter($node, fn ($value) => $value !== null && $value !== '' && $value !== []);
    }

    protected static function loadBranches()
    {
        try {
            return Branch::active()->ordered()->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    protected static function menuServices()
    {
        try {
            return \App\Models\Service::active()->where('in_menu', true)->ordered()->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    protected static function webPage(string $canonical, array $seo, string $pageImage, string $base): array
    {
        $node = [
            '@type' => $seo['type'] ?? 'WebPage',
            '@id' => $canonical . '#webpage',
            'url' => $canonical,
            'name' => $seo['title'],
            'description' => $seo['description'],
            'inLanguage' => 'ar-SA',
            'isPartOf' => ['@id' => $base . '/#website'],
            'about' => ['@id' => $base . '/#business'],
        ];

        if ($pageImage) {
            $node['primaryImageOfPage'] = ['@type' => 'ImageObject', 'url' => $pageImage];
        }

        if (! empty($seo['updated'])) {
            $node['dateModified'] = $seo['updated'];
        }

        if (! empty($seo['crumbs'])) {
            $node['breadcrumb'] = ['@id' => $canonical . '#breadcrumb'];
        }

        return array_filter($node, fn ($value) => $value !== null && $value !== '');
    }

    protected static function service($service, string $canonical, string $base, string $pageImage): array
    {
        $node = [
            '@type' => 'Service',
            '@id' => $canonical . '#service',
            'name' => $service->title,
            'serviceType' => $service->title,
            'description' => $service->meta_description ?: $service->excerpt,
            'url' => $canonical,
            'image' => absolute_media($service->hero_image ?: $service->card_image) ?: $pageImage,
            'provider' => ['@id' => $base . '/#business'],
            'areaServed' => array_map(
                fn ($city) => ['@type' => 'City', 'name' => $city],
                array_values(array_filter(Setting::list('seo_areas')))
            ),
        ];

        $items = $service->relationLoaded('items') ? $service->items : collect();
        if ($items->isNotEmpty()) {
            $node['hasOfferCatalog'] = [
                '@type' => 'OfferCatalog',
                'name' => $service->title,
                'itemListElement' => $items->values()->map(fn ($item) => [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => $item->title,
                        'description' => collect($item->points)->take(3)->implode('، ') ?: null,
                    ],
                ])->all(),
            ];
        }

        return array_filter($node, fn ($value) => $value !== null && $value !== '' && $value !== []);
    }
}
