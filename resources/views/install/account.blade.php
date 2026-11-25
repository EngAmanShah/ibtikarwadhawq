@extends('install.layout')
@section('content')
<h1>حساب المدير</h1>
<p>هذه البيانات ستستخدمها للدخول إلى لوحة التحكم. احفظها في مكان آمن.</p>
<form method="post" action="{{ route('install.finish') }}">
  @csrf
  <div class="fields">
    <div class="field"><label for="site_name">اسم الموقع</label><input id="site_name" class="input" name="site_name" value="{{ old('site_name', 'ابتكار وذوق للمقاولات') }}" required></div>
    <div class="field half"><label for="name">اسمك</label><input id="name" class="input" name="name" value="{{ old('name') }}" required></div>
    <div class="field half"><label for="email">البريد الإلكتروني</label><input id="email" class="input ltr" type="email" name="email" value="{{ old('email') }}" required></div>
    <div class="field half"><label for="password">كلمة المرور (8 أحرف على الأقل)</label><input id="password" class="input" type="password" name="password" required minlength="8"></div>
    <div class="field half"><label for="password_confirmation">تأكيد كلمة المرور</label><input id="password_confirmation" class="input" type="password" name="password_confirmation" required></div>
  </div>
  <div class="alert alert-info" style="margin-top:18px"><i class="fa-solid fa-circle-info"></i><span>عند الضغط على «تثبيت» سيتم إنشاء الجداول ونقل كل محتوى الموقع الحالي تلقائيًا.</span></div>
  <div class="wizard-foot">
    <a class="btn btn-ghost" href="{{ route('install.database') }}"><i class="fa-solid fa-arrow-right"></i> السابق</a>
    <button class="btn btn-gold btn-lg" data-loading><span class="spinner"></span><i class="fa-solid fa-wand-magic-sparkles btn-label-ico"></i> تثبيت الآن</button>
  </div>
</form>
@endsection
