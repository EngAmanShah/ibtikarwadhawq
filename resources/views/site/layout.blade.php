@php
    $siteUrl = rtrim(setting('site_url') ?: url('/'), '/');
    $canonical = $siteUrl . ($seo['path'] === '/' ? '/' : $seo['path']);
    $ogImage = media($seo['image'] ?? setting('og_image'));
    if ($ogImage && ! str_starts_with($ogImage, 'http')) $ogImage = url($ogImage);
    $gtm = trim((string) setting('gtm_id'));
    $siteName = setting('site_name');
    $socials = collect(['instagram' => ['fa-instagram', 'إنستجرام'], 'snapchat' => ['fa-snapchat', 'سناب شات'], 'tiktok' => ['fa-tiktok', 'تيك توك'], 'x' => ['fa-x-twitter', 'منصة X'], 'facebook' => ['fa-facebook-f', 'فيسبوك'], 'linkedin' => ['fa-linkedin-in', 'لينكد إن'], 'youtube' => ['fa-youtube', 'يوتيوب']])
        ->map(fn ($meta, $k) => ['url' => setting("social_$k"), 'icon' => $meta[0], 'label' => $meta[1]])
        ->filter(fn ($s) => ! empty($s['url']));
    $primary = setting('primary_color');
    $primaryDark = setting('primary_dark');
    $current = request()->path();
    $isActive = fn ($p) => $p === '' ? $current === '/' : str_starts_with($current, $p);

    $graph = [
        [
            '@type' => ['LocalBusiness', 'HomeAndConstructionBusiness'],
            '@id' => $siteUrl . '/#business',
            'name' => $siteName,
            'url' => $siteUrl . '/',
            'logo' => url(media(setting('logo_icon'))),
            'image' => url(media(setting('logo_icon'))),
            'email' => setting('email'),
            'telephone' => setting('phone_main'),
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => setting('seo_city'), 'addressRegion' => setting('seo_region'), 'postalCode' => setting('seo_postal'), 'addressCountry' => 'SA'],
            'areaServed' => collect(\App\Models\Setting::list('seo_areas'))->map(fn ($c) => ['@type' => 'City', 'name' => $c])->values()->all(),
            'sameAs' => $socials->pluck('url')->values()->all(),
            'contactPoint' => $branches->map(fn ($b) => ['@type' => 'ContactPoint', 'telephone' => $b->phone_link, 'contactType' => 'customer service', 'areaServed' => 'SA', 'availableLanguage' => 'Arabic'])->values()->all(),
        ],
        ['@type' => 'WebSite', '@id' => $siteUrl . '/#website', 'url' => $siteUrl . '/', 'name' => $siteName, 'inLanguage' => 'ar-SA', 'publisher' => ['@id' => $siteUrl . '/#business']],
        [
            '@type' => 'BreadcrumbList',
            '@id' => $canonical . '#breadcrumb',
            'itemListElement' => collect([['الرئيسية', '/']])->merge($seo['crumbs'] ?? [])->values()->map(fn ($c, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $c[0], 'item' => $siteUrl . ($c[1] === '/' ? '/' : $c[1])])->all(),
        ],
        ['@type' => 'WebPage', '@id' => $canonical . '#webpage', 'url' => $canonical, 'name' => $seo['title'], 'description' => $seo['description'], 'inLanguage' => 'ar-SA', 'isPartOf' => ['@id' => $siteUrl . '/#website']],
    ];
    if (! empty($seo['service'])) {
        $graph[] = ['@type' => 'Service', '@id' => $canonical . '#service', 'name' => $seo['service']->title, 'description' => $seo['description'], 'url' => $canonical, 'image' => $ogImage, 'provider' => ['@id' => $siteUrl . '/#business'], 'serviceType' => $seo['service']->title];
    }
@endphp
<!doctype html>
<html dir="rtl" lang="ar">
<head>
@if($gtm)
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',@json($gtm));</script>
<!-- End Google Tag Manager -->
@endif
<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<title>{{ $seo['title'] }}</title>
<meta content="{{ $seo['description'] }}" name="description" />
<link href="{{ $canonical }}" rel="canonical" />
<meta content="{{ $seo['title'] }}" property="og:title" />
<meta content="{{ $seo['description'] }}" property="og:description" />
<meta content="website" property="og:type" />
<meta content="ar_SA" property="og:locale" />
<meta content="{{ $ogImage }}" property="og:image" />
<meta content="{{ $siteName }}" property="og:site_name" />
<meta content="{{ $canonical }}" property="og:url" />
<meta content="summary_large_image" name="twitter:card" />
<meta content="{{ $seo['title'] }}" name="twitter:title" />
<meta content="{{ $seo['description'] }}" name="twitter:description" />
<meta content="{{ $ogImage }}" name="twitter:image" />
<meta content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" name="robots" />
@if(setting('google_verification'))<meta content="{{ setting('google_verification') }}" name="google-site-verification" />@endif
<link href="{{ media(setting('favicon')) }}" rel="icon" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
@stack('head')
<link href="{{ asset('assets/css/style.css') }}?v={{ @filemtime(public_path('assets/css/style.css')) }}" rel="stylesheet" />
@if($primary && ($primary !== '#bfa27e' || $primaryDark !== '#a88c66'))
<style>:root{--primary:{{ $primary }};--primary-dark:{{ $primaryDark ?: $primary }};}</style>
@endif
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
{!! setting('custom_head_code') !!}
</head>
<body>
@if($gtm)
<noscript><iframe height="0" src="https://www.googletagmanager.com/ns.html?id={{ $gtm }}" style="display:none;visibility:hidden" width="0"></iframe></noscript>
@endif

<header class="site-header" id="siteHeader">
  <div class="header-outer">
    <div class="header-shell">
      <a class="brand" href="{{ url('/') }}" aria-label="{{ setting('brand_name') }}">
        <img class="brand-icon" src="{{ media(setting('logo_icon')) }}" alt="شعار {{ setting('brand_name') }}">
        <span class="brand-text"><strong>{{ setting('brand_name') }}</strong><small>{{ setting('brand_suffix') }}</small></span>
      </a>
      <nav class="desktop-nav" aria-label="القائمة الرئيسية">
        <a class="{{ $isActive('') ? 'active' : '' }}" href="{{ url('/') }}">الرئيسية</a>
        <a class="{{ $isActive('about') ? 'active' : '' }}" href="{{ url('/about') }}">عن {{ setting('brand_name') }}</a>
        <div class="nav-dropdown">
          <a class="{{ $isActive('services') ? 'active' : '' }}" href="{{ url('/services') }}">نطاق الأعمال <i class="fa-solid fa-chevron-down"></i></a>
          <div class="dropdown-panel"><div class="dropdown-grid">
            @foreach($menuServices as $s)
              <a href="{{ url('/services/' . $s->slug) }}"><span class="service-menu-icon"><i class="{{ fa($s->icon) }}"></i></span><span><strong>{{ $s->title }}</strong><small>{{ $s->subtitle }}</small></span></a>
            @endforeach
          </div></div>
        </div>
        <a class="{{ $isActive('documents') ? 'active' : '' }}" href="{{ url('/documents') }}">الوثائق الرسمية</a>
        <a class="{{ $isActive('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">تواصل معنا</a>
      </nav>
      <button class="menu-toggle" id="menuToggle" aria-label="فتح القائمة"><span></span><span></span><span></span></button>
      @if(setting('profile_pdf'))
      <a target="_blank" class="header-cta" href="{{ media(setting('profile_pdf')) }}"><i class="fa-solid fa-file-pdf" style="margin-left:5px;"></i> {{ setting('profile_label') }}</a>
      @endif
    </div>
  </div>
</header>

<div class="mobile-layer" id="mobileLayer"></div>
<aside class="mobile-menu" id="mobileMenu" aria-label="قائمة الجوال">
  <div class="mobile-head">
    <div class="mobile-brand">
      <img class="brand-icon" src="{{ media(setting('logo_icon')) }}" alt="شعار {{ setting('brand_name') }}">
      <span class="brand-text"><strong>{{ setting('brand_name') }}</strong><small>{{ setting('brand_suffix') }}</small></span>
    </div>
    <button id="menuClose" aria-label="إغلاق القائمة"><i class="fa-solid fa-xmark"></i></button>
  </div>
  <nav class="mobile-links">
    <a href="{{ url('/') }}">الرئيسية</a>
    <a href="{{ url('/about') }}">عن {{ setting('brand_name') }}</a>
    <button class="mobile-services-toggle" id="mobileServicesToggle">نطاق الأعمال <i class="fa-solid fa-chevron-down"></i></button>
    <div class="mobile-services" id="mobileServices">
      @foreach($menuServices as $s)
        <a class="mobile-service-link" href="{{ url('/services/' . $s->slug) }}"><span class="service-menu-icon"><i class="{{ fa($s->icon) }}"></i></span><span><strong>{{ $s->title }}</strong><small>{{ $s->subtitle }}</small></span></a>
      @endforeach
    </div>
    <a href="{{ url('/documents') }}">الوثائق الرسمية</a>
    <a href="{{ url('/contact') }}">تواصل معنا</a>
  </nav>
</aside>

<main>
@yield('content')
</main>

<footer class="site-footer">
  <div class="footer-shell">
    <div class="footer-brand">
      <div class="footer-brand-logo"><img src="{{ media(setting('footer_logo')) }}" alt="{{ setting('brand_name') }}"></div>
      <p>{{ setting('footer_text') }}</p>
      @if($socials->count())
      <div class="footer-social" aria-label="حسابات التواصل الاجتماعي">
        @foreach($socials as $s)
          <a href="{{ $s['url'] }}" target="_blank" rel="noopener" aria-label="{{ $s['label'] }}"><i class="fa-brands {{ $s['icon'] }}"></i></a>
        @endforeach
      </div>
      @endif
    </div>
    <div class="footer-col">
      <h3>روابط سريعة</h3>
      <a href="{{ url('/about') }}">عن {{ setting('brand_name') }}</a>
      <a href="{{ url('/services') }}">نطاق الأعمال</a>
      <a href="{{ url('/documents') }}">الوثائق الرسمية</a>
      <a href="{{ url('/contact') }}">تواصل معنا</a>
    </div>
    <div class="footer-col">
      <h3>الخدمات</h3>
      @foreach($menuServices as $s)<a href="{{ url('/services/' . $s->slug) }}">{{ $s->title }}</a>@endforeach
    </div>
    <div class="footer-col footer-contact-col">
      <h3>بيانات التواصل</h3>
      @foreach($branches as $b)
      <div class="footer-branch">
        <strong>{{ $b->title }}</strong>
        @if($b->phone)<a href="tel:{{ $b->phone_link }}"><i class="fa-solid fa-phone"></i>{{ $b->phone_display }}</a>@endif
        @if($b->address)<span><i class="fa-solid fa-location-dot"></i>{{ $b->address }}</span>@endif
      </div>
      @endforeach
      @if(setting('whatsapp_main'))<a href="https://wa.me/{{ preg_replace('/\D+/', '', setting('whatsapp_main')) }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i>واتساب مباشر</a>@endif
      @if(setting('email'))<a href="mailto:{{ setting('email') }}"><i class="fa-solid fa-envelope"></i>{{ setting('email') }}</a>@endif
    </div>
  </div>
  <div class="footer-bottom">
    <span>© {{ date('Y') }} {{ setting('copyright_text') }}</span>
    <span>تصميم وبرمجة الموقع | <a href="https://vectoorize.com/" target="_blank" rel="noopener">فيكتورايز</a></span>
  </div>
</footer>

@php $waBranches = $branches->filter(fn ($b) => $b->whatsapp_number); @endphp
@if($waBranches->count())
<div class="floating-contact floating-contact-menu" aria-label="تواصل سريع">
  <button class="floating-btn floating-main-btn" type="button" id="floatingContactToggle" aria-label="{{ setting('floating_label') }}" aria-expanded="false">
    <i class="fa-brands fa-whatsapp"></i><span>{{ setting('floating_label') }}</span>
  </button>
  <div class="floating-whatsapp-panel" id="floatingWhatsappPanel" aria-label="أرقام واتساب الفروع">
    @foreach($waBranches as $b)
      <a href="https://wa.me/{{ $b->whatsapp_number }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i><span>واتساب {{ $b->title }}</span></a>
    @endforeach
  </div>
</div>
@endif

<script src="{{ asset('assets/js/site.js') }}?v={{ @filemtime(public_path('assets/js/site.js')) }}"></script>
@stack('scripts')
<script src="{{ asset('assets/js/main.js') }}?v={{ @filemtime(public_path('assets/js/main.js')) }}"></script>
</body>
</html>
