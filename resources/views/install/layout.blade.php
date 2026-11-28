<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex"><meta name="csrf-token" content="{{ csrf_token() }}">
  <title>تثبيت لوحة التحكم</title>
  <link rel="icon" href="{{ asset('assets/images/brand/favicon.png') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/studio.css') }}?v={{ @filemtime(public_path('admin-assets/studio.css')) }}">
</head>
<body>
<div class="wizard">
  <div class="wizard-top"><img src="{{ asset('assets/images/brand/logo-icon.png') }}" alt=""><div><strong>تثبيت لوحة تحكم الموقع</strong><small>خطوات بسيطة وينتهي كل شيء خلال دقيقة</small></div></div>
  <div class="steps">
    @foreach(['مرحبًا', 'فحص الخادم', 'قاعدة البيانات', 'حساب المدير', 'تم'] as $i => $s)
      <div class="step {{ $step > $i + 1 ? 'done' : ($step === $i + 1 ? 'now' : '') }}"><b>@if($step > $i + 1)<i class="fa-solid fa-check"></i>@else{{ $i + 1 }}@endif</b><span>{{ $s }}</span></div>
    @endforeach
  </div>
  <div class="card wizard-card rise">
    @if($errors->any())<div class="alert alert-error" style="margin-bottom:18px"><i class="fa-solid fa-circle-exclamation"></i><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
    @yield('content')
  </div>
</div>
<script src="{{ asset('admin-assets/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
