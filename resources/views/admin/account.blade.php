@extends('admin.layouts.app')
@section('title', 'حسابي')
@section('crumbs')<strong>حسابي</strong>@endsection

@section('content')
<div class="page-head rise">
  <div class="page-head-icon"><i class="fa-solid fa-user-gear"></i></div>
  <div><h1>حسابي</h1><p>غيّر اسمك وبريد الدخول وكلمة المرور.</p></div>
</div>
<form class="card rise" method="post" action="{{ route('admin.account.update') }}" style="max-width:760px">
  @csrf @method('put')
  <div class="card-body">
    <div class="fields">
      <div class="field half"><label for="name">الاسم</label><input id="name" class="input" name="name" value="{{ old('name', $user->name) }}" required></div>
      <div class="field half"><label for="email">البريد الإلكتروني (للدخول)</label><input id="email" class="input ltr" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
      <div class="field"><div class="section-hint" style="margin:0"><i class="fa-solid fa-key"></i><span>لتغيير كلمة المرور اكتب الحالية ثم الجديدة. اترك الحقول فارغة إن لم ترد التغيير.</span></div></div>
      <div class="field third"><label for="cp">كلمة المرور الحالية</label><input id="cp" class="input" type="password" name="current_password" autocomplete="current-password"></div>
      <div class="field third"><label for="np">كلمة المرور الجديدة</label><input id="np" class="input" type="password" name="password" autocomplete="new-password"></div>
      <div class="field third"><label for="np2">تأكيد الجديدة</label><input id="np2" class="input" type="password" name="password_confirmation" autocomplete="new-password"></div>
    </div>
    <div style="margin-top:20px"><button class="btn btn-gold" data-loading><span class="spinner"></span><i class="fa-solid fa-floppy-disk btn-label-ico"></i> حفظ</button></div>
  </div>
</form>
@endsection
