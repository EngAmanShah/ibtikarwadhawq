@php
    $user = auth()->user();
    $nav = [
        ['title' => null, 'items' => [
            ['t' => 'نظرة عامة', 'i' => 'fa-gauge-high', 'u' => route('admin.dashboard'), 'on' => request()->routeIs('admin.dashboard')],
            ['t' => 'طلبات العملاء', 'i' => 'fa-inbox', 'u' => route('admin.leads.index'), 'on' => request()->routeIs('admin.leads.*'), 'count' => $newLeads ?? 0],
        ]],
        ['title' => 'صفحات الموقع', 'items' => [
            ['t' => 'الصفحة الرئيسية', 'i' => 'fa-house-chimney', 'u' => route('admin.pages.edit', 'home'), 'on' => request()->is('admin/pages/home')],
            ['t' => 'من نحن', 'i' => 'fa-building-user', 'u' => route('admin.pages.edit', 'about'), 'on' => request()->is('admin/pages/about') || in_array(request()->route('group'), ['about_feature', 'about_card', 'about_value'])],
            ['t' => 'نطاق الأعمال', 'i' => 'fa-briefcase', 'u' => route('admin.pages.edit', 'services_page'), 'on' => request()->is('admin/pages/services_page')],
            ['t' => 'الوثائق الرسمية', 'i' => 'fa-file-shield', 'u' => route('admin.pages.edit', 'documents_page'), 'on' => request()->is('admin/pages/documents_page') || request()->route('group') === 'company_fact'],
            ['t' => 'تواصل معنا', 'i' => 'fa-envelope-open-text', 'u' => route('admin.pages.edit', 'contact_page'), 'on' => request()->is('admin/pages/contact_page')],
        ]],
        ['title' => 'المحتوى', 'items' => [
            ['t' => 'الخدمات وتفاصيلها', 'i' => 'fa-layer-group', 'u' => route('admin.services.index'), 'on' => request()->routeIs('admin.services.*')],
            ['t' => 'شريط التشطيبات', 'i' => 'fa-film', 'u' => route('admin.slides.index'), 'on' => request()->routeIs('admin.slides.*')],
            ['t' => 'العملاء', 'i' => 'fa-handshake', 'u' => route('admin.clients.index'), 'on' => request()->routeIs('admin.clients.*')],
            ['t' => 'الوثائق والشهادات', 'i' => 'fa-file-pdf', 'u' => route('admin.documents.index'), 'on' => request()->routeIs('admin.documents.*')],
            ['t' => 'الفروع والأرقام', 'i' => 'fa-map-location-dot', 'u' => route('admin.branches.index'), 'on' => request()->routeIs('admin.branches.*')],
        ]],
        ['title' => 'الإعدادات', 'items' => [
            ['t' => 'الهوية والشعارات', 'i' => 'fa-swatchbook', 'u' => route('admin.pages.edit', 'identity'), 'on' => request()->is('admin/pages/identity')],
            ['t' => 'التواصل والسوشيال', 'i' => 'fa-address-book', 'u' => route('admin.pages.edit', 'contact_info'), 'on' => request()->is('admin/pages/contact_info')],
            ['t' => 'الظهور في جوجل', 'i' => 'fa-magnifying-glass-chart', 'u' => route('admin.pages.edit', 'seo'), 'on' => request()->is('admin/pages/seo')],
        ]],
    ];

    $palette = [];
    foreach ($nav as $g) foreach ($g['items'] as $it) $palette[] = ['t' => $it['t'], 'i' => $it['i'], 'u' => $it['u'], 'g' => $g['title'] ?? 'عام'];
    $palette = array_merge($palette, [
        ['t' => 'إضافة خدمة جديدة', 'i' => 'fa-plus', 'u' => route('admin.services.create'), 'g' => 'إجراء سريع', 'k' => 'جديد'],
        ['t' => 'إضافة شعار عميل', 'i' => 'fa-plus', 'u' => route('admin.clients.create'), 'g' => 'إجراء سريع', 'k' => 'جديد'],
        ['t' => 'رفع وثيقة جديدة', 'i' => 'fa-upload', 'u' => route('admin.documents.create'), 'g' => 'إجراء سريع', 'k' => 'جديد pdf'],
        ['t' => 'إضافة صورة لشريط التشطيبات', 'i' => 'fa-plus', 'u' => route('admin.slides.create'), 'g' => 'إجراء سريع', 'k' => 'جديد'],
        ['t' => 'إضافة فرع', 'i' => 'fa-plus', 'u' => route('admin.branches.create'), 'g' => 'إجراء سريع', 'k' => 'جديد رقم هاتف'],
        ['t' => 'تغيير الشعار', 'i' => 'fa-image', 'u' => route('admin.pages.edit', 'identity'), 'g' => 'إجراء سريع', 'k' => 'لوجو'],
        ['t' => 'تغيير لون الموقع', 'i' => 'fa-palette', 'u' => route('admin.pages.edit', 'identity') . '#sec-2', 'g' => 'إجراء سريع', 'k' => 'الوان'],
        ['t' => 'تغيير ملف البروفايل', 'i' => 'fa-file-pdf', 'u' => route('admin.pages.edit', 'identity') . '#sec-3', 'g' => 'إجراء سريع', 'k' => 'pdf'],
        ['t' => 'روابط السوشيال ميديا', 'i' => 'fa-share-nodes', 'u' => route('admin.pages.edit', 'contact_info') . '#sec-1', 'g' => 'إجراء سريع', 'k' => 'انستجرام تيك توك سناب'],
        ['t' => 'بطاقات المميزات — من نحن', 'i' => 'fa-table-cells-large', 'u' => route('admin.blocks.index', 'about_feature'), 'g' => 'من نحن'],
        ['t' => 'الرؤية والرسالة', 'i' => 'fa-bullseye', 'u' => route('admin.blocks.index', 'about_card'), 'g' => 'من نحن'],
        ['t' => 'قيمنا', 'i' => 'fa-heart', 'u' => route('admin.blocks.index', 'about_value'), 'g' => 'من نحن'],
        ['t' => 'بيانات السجل التجاري', 'i' => 'fa-fingerprint', 'u' => route('admin.blocks.index', 'company_fact'), 'g' => 'الوثائق'],
        ['t' => 'حسابي وكلمة المرور', 'i' => 'fa-user-gear', 'u' => route('admin.account'), 'g' => 'الحساب', 'k' => 'باسورد'],
        ['t' => 'فتح الموقع', 'i' => 'fa-arrow-up-right-from-square', 'u' => url('/'), 'g' => 'الموقع', 'x' => 1],
    ]);
    try {
        foreach (\App\Models\Service::ordered()->get(['id', 'title']) as $svc) {
            $palette[] = ['t' => 'تعديل: ' . $svc->title, 'i' => 'fa-pen', 'u' => route('admin.services.edit', $svc), 'g' => 'الخدمات'];
        }
    } catch (\Throwable $e) {}
@endphp
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="noindex, nofollow">
  <title>@yield('title', 'لوحة التحكم') — {{ setting('brand_name') }}</title>
  <link rel="icon" href="{{ media(setting('favicon')) }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/admin.css') }}?v={{ @filemtime(public_path('admin-assets/admin.css')) }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/studio.css') }}?v={{ @filemtime(public_path('admin-assets/studio.css')) }}">
  @stack('head')
</head>
<body>
<div class="shell">
  <aside class="rail" aria-label="القائمة الرئيسية">
    <a class="rail-brand" href="{{ route('admin.dashboard') }}">
      <img src="{{ media(setting('logo_icon')) }}" alt="">
      <span><strong>{{ setting('brand_name') }}</strong><small>لوحة إدارة الموقع</small></span>
    </a>
    <nav class="rail-nav">
      @foreach($nav as $group)
        <div class="rail-group">
          @if($group['title'])<div class="rail-group-title">{{ $group['title'] }}</div>@endif
          @foreach($group['items'] as $it)
            <a class="rail-link {{ $it['on'] ? 'active' : '' }}" href="{{ $it['u'] }}">
              <span class="ico"><i class="fa-solid {{ $it['i'] }}"></i></span>
              <span>{{ $it['t'] }}</span>
              @if(! empty($it['count']))<span class="count">{{ $it['count'] }}</span>@endif
            </a>
          @endforeach
        </div>
      @endforeach
    </nav>
    <div class="rail-foot">
      <div class="rail-user">
        <a href="{{ route('admin.account') }}" class="avatar" title="حسابي">{{ mb_substr($user->name, 0, 1) }}</a>
        <a href="{{ route('admin.account') }}"><strong>{{ $user->name }}</strong><small>مدير الموقع</small></a>
        <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="icon-btn-dark" title="تسجيل الخروج" aria-label="تسجيل الخروج"><i class="fa-solid fa-arrow-right-from-bracket"></i></button></form>
      </div>
    </div>
  </aside>
  <div class="rail-back" data-rail-toggle></div>

  <div class="main">
    <header class="topbar">
      <button class="top-icon topbar-menu" data-rail-toggle aria-label="القائمة"><i class="fa-solid fa-bars-staggered"></i></button>
      <div class="crumbs">
        <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house" style="font-size:12px;opacity:1"></i></a>
        @hasSection('crumbs')<i class="fa-solid fa-chevron-left"></i>@yield('crumbs')@endif
      </div>
      <button class="search-trigger" type="button" data-palette><i class="fa-solid fa-magnifying-glass"></i><span>ابحث عن أي صفحة أو إجراء…</span><kbd>Ctrl K</kbd></button>
      <div class="top-actions">
        <a class="top-icon" href="{{ route('admin.leads.index') }}" title="طلبات العملاء"><i class="fa-solid fa-bell"></i>@if(($newLeads ?? 0) > 0)<span class="dot">{{ $newLeads }}</span>@endif</a>
        <a class="btn btn-dark hide-sm" href="@yield('preview', url('/'))" target="_blank"><i class="fa-solid fa-eye"></i> معاينة الموقع</a>
      </div>
    </header>

    @if($errors->any())
      <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i><div><strong>يرجى مراجعة الحقول التالية:</strong><ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div></div>
    @endif

    @yield('content')
  </div>
</div>

<div class="toasts">
  @if(session('ok'))<div class="toast"><i class="t-ico fa-solid fa-check"></i><span>{{ session('ok') }}</span><button type="button" aria-label="إغلاق"><i class="fa-solid fa-xmark"></i></button></div>@endif
  @if(session('error'))<div class="toast error"><i class="t-ico fa-solid fa-triangle-exclamation"></i><span>{{ session('error') }}</span><button type="button" aria-label="إغلاق"><i class="fa-solid fa-xmark"></i></button></div>@endif
</div>

<div class="modal-back" id="confirmModal" role="dialog" aria-modal="true">
  <div class="modal">
    <div class="m-ico"><i class="fa-solid fa-trash-can"></i></div>
    <h3 id="confirmTitle">هل أنت متأكد؟</h3>
    <p id="confirmText">لا يمكن التراجع عن هذا الإجراء.</p>
    <div class="m-actions">
      <button class="btn btn-ghost" data-modal-close type="button">إلغاء</button>
      <button class="btn btn-danger" id="confirmYes" type="button" style="background:var(--red);color:#fff"><i class="fa-solid fa-trash-can"></i> نعم، احذف</button>
    </div>
  </div>
</div>

<div class="palette-back" id="palette">
  <div class="palette" role="dialog" aria-label="البحث السريع">
    <div class="palette-input"><i class="fa-solid fa-magnifying-glass"></i><input placeholder="اكتب ما تريد تعديله… مثال: الشعار، الأرقام، خدمة" aria-label="بحث"></div>
    <div class="palette-list"></div>
    <div class="palette-foot"><span><kbd>↑</kbd> <kbd>↓</kbd> للتنقل</span><span><kbd>Enter</kbd> للفتح</span><span><kbd>Esc</kbd> للإغلاق</span></div>
  </div>
</div>

<script>window.PALETTE = @json($palette);</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script src="{{ asset('admin-assets/admin.js') }}?v={{ @filemtime(public_path('admin-assets/admin.js')) }}"></script>
@stack('scripts')
</body>
</html>
