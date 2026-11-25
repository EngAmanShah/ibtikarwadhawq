@extends('install.layout')
@section('content')
<div style="text-align:center;padding:20px 0">
  <div class="success-burst"><i class="fa-solid fa-check"></i></div>
  <h1>تم التثبيت بنجاح</h1>
  <p style="color:var(--muted);margin:6px 0 24px">الموقع ولوحة التحكم جاهزان. تم إغلاق معالج التثبيت تلقائيًا للحماية.</p>
  <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
    <a class="btn btn-gold btn-lg" href="{{ route('admin.login') }}"><i class="fa-solid fa-gauge-high"></i> الدخول للوحة التحكم</a>
    <a class="btn btn-ghost btn-lg" href="{{ url('/') }}" target="_blank"><i class="fa-solid fa-globe"></i> عرض الموقع</a>
  </div>
  <p class="help" style="justify-content:center;margin-top:20px"><i class="fa-solid fa-bookmark"></i> رابط لوحة التحكم: <b dir="ltr">{{ url('/admin') }}</b></p>
</div>
@endsection
