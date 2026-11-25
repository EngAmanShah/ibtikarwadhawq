@extends('install.layout')
@section('content')
<h1>فحص الخادم</h1>
<p>نتأكد أن الاستضافة جاهزة لتشغيل لوحة التحكم.</p>
<div class="req-list">
  @foreach($checks as [$label, $pass, $note])
    <div class="req {{ $pass ? 'ok' : 'bad' }}"><i class="fa-solid {{ $pass ? 'fa-check' : 'fa-xmark' }}"></i><span>{{ $label }}</span><small>{{ $note }}</small></div>
  @endforeach
</div>
@unless($ok)
  <div class="alert alert-error" style="margin-top:18px"><i class="fa-solid fa-triangle-exclamation"></i><span>بعض المتطلبات غير متوفرة. فعّلها من إعدادات PHP في لوحة الاستضافة (Select PHP Version)، ولصلاحيات الكتابة اجعل صلاحية المجلدات 755 أو 775، ثم أعد الفحص.</span></div>
@endunless
<div class="wizard-foot">
  <a class="btn btn-ghost" href="{{ route('install.welcome') }}"><i class="fa-solid fa-arrow-right"></i> السابق</a>
  @if($ok)<a class="btn btn-gold btn-lg" href="{{ route('install.database') }}">التالي <i class="fa-solid fa-arrow-left"></i></a>
  @else<a class="btn btn-dark" href="{{ route('install.requirements') }}"><i class="fa-solid fa-rotate"></i> إعادة الفحص</a>@endif
</div>
@endsection
