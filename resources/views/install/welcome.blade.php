@extends('install.layout')
@section('content')
<h1><i class="fa-solid fa-hand-sparkles" style="color:var(--gold)"></i> أهلًا بك</h1>
<p>سنقوم بتثبيت لوحة التحكم وربطها بالموقع. ستحتاج فقط إلى بيانات قاعدة بيانات MySQL من لوحة الاستضافة (cPanel / Plesk).</p>
<div class="feature-tiles">
  <div><i class="fa-solid fa-database"></i><strong>جهّز قاعدة بيانات</strong><small>أنشئ قاعدة بيانات ومستخدمًا لها من لوحة الاستضافة.</small></div>
  <div><i class="fa-solid fa-wand-magic-sparkles"></i><strong>تثبيت تلقائي</strong><small>سيتم نقل كل محتوى الموقع الحالي إلى القاعدة تلقائيًا.</small></div>
  <div><i class="fa-solid fa-user-shield"></i><strong>حساب آمن</strong><small>أنشئ حساب المدير لتدخل به لوحة التحكم.</small></div>
</div>
<div class="wizard-foot"><span class="help"><i class="fa-solid fa-clock"></i> يستغرق أقل من دقيقتين</span><a class="btn btn-gold btn-lg" href="{{ route('install.requirements') }}">ابدأ التثبيت <i class="fa-solid fa-arrow-left"></i></a></div>
@endsection
