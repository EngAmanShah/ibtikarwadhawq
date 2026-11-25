<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
  <title>تسجيل الدخول — {{ setting('brand_name') }}</title>
  <link rel="icon" href="{{ media(setting('favicon')) }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('admin-assets/admin.css') }}?v={{ @filemtime(public_path('admin-assets/admin.css')) }}">
  <link rel="stylesheet" href="{{ asset('admin-assets/studio.css') }}?v={{ @filemtime(public_path('admin-assets/studio.css')) }}">
</head>
<body class="login">
<div class="login-split">
  <aside class="login-side">
    <div class="ls-top"><img src="{{ media(setting('logo_icon')) }}" alt=""><span>{{ setting('site_name') }}</span></div>
    <div>
      <h2>{{ setting('brand_name') }}<small>لوحة إدارة الموقع</small></h2>
      <div class="ls-rule"></div>
      <p>تعديل النصوص والصور والخدمات وبيانات التواصل، ومتابعة طلبات العملاء من مكان واحد.</p>
    </div>
    <div class="ls-foot"><span>{{ preg_replace('#^https?://#', '', rtrim(setting('site_url') ?: url('/'), '/')) }}</span><span>{{ date('Y') }}</span></div>
  </aside>

  <main class="login-main">
    <div class="login-box">
      <h1>تسجيل الدخول</h1>
      <p>أدخل بيانات حسابك للمتابعة</p>

      @if($errors->any())<div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i><span>{{ $errors->first() }}</span></div>@endif

      <form method="post" action="{{ route('admin.login.attempt') }}">
        @csrf
        <div class="field"><label for="email">البريد الإلكتروني</label><div class="input-wrap"><i class="fa-regular fa-envelope"></i><input id="email" class="input ltr" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"></div></div>
        <div class="field"><label for="password">كلمة المرور</label><div class="input-wrap"><i class="fa-solid fa-lock"></i><input id="password" class="input" type="password" name="password" required autocomplete="current-password"><button type="button" class="pass-toggle" aria-label="إظهار كلمة المرور"><i class="fa-solid fa-eye"></i></button></div></div>
        <label class="switch"><input type="checkbox" name="remember" value="1" checked><span class="track"></span><span>تذكرني على هذا الجهاز</span></label>
        <button class="btn btn-gold btn-lg" data-loading><span class="spinner"></span>دخول <i class="fa-solid fa-arrow-left btn-label-ico"></i></button>
      </form>

      <a class="login-back" href="{{ url('/') }}"><i class="fa-solid fa-arrow-right"></i> العودة إلى الموقع</a>
    </div>
  </main>
</div>
<script src="{{ asset('admin-assets/admin.js') }}"></script>
</body>
</html>
