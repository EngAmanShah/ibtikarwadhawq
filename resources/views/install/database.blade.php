@extends('install.layout')
@section('content')
<h1>ربط قاعدة البيانات</h1>
<p>انسخ البيانات من قسم «MySQL Databases» في لوحة الاستضافة.</p>
<form id="dbForm" method="post" action="{{ route('install.database.save') }}">
  @csrf
  <div class="fields">
    <div class="field half"><label for="host">عنوان الخادم</label><input id="host" class="input ltr" name="host" value="{{ old('host', 'localhost') }}" required><div class="help"><i class="fa-solid fa-circle-info"></i><span>غالبًا يكون localhost</span></div></div>
    <div class="field half"><label for="port">المنفذ</label><input id="port" class="input ltr" name="port" value="{{ old('port', '3306') }}" required></div>
    <div class="field"><label for="database">اسم قاعدة البيانات</label><input id="database" class="input ltr" name="database" value="{{ old('database') }}" required placeholder="مثال: cpaneluser_site"></div>
    <div class="field half"><label for="username">اسم المستخدم</label><input id="username" class="input ltr" name="username" value="{{ old('username') }}" required></div>
    <div class="field half"><label for="password">كلمة المرور</label><div class="input-wrap"><input id="password" class="input ltr" type="password" name="password"><button type="button" class="pass-toggle" aria-label="إظهار"><i class="fa-solid fa-eye"></i></button></div></div>
  </div>
  <div id="dbResult" style="margin-top:16px"></div>
  <div class="wizard-foot">
    <button type="button" class="btn btn-ghost" id="testBtn"><i class="fa-solid fa-plug"></i> اختبار الاتصال</button>
    <button class="btn btn-gold btn-lg" data-loading><span class="spinner"></span>حفظ ومتابعة <i class="fa-solid fa-arrow-left btn-label-ico"></i></button>
  </div>
</form>
@endsection
@push('scripts')
<script>
document.getElementById('testBtn').addEventListener('click', async (e) => {
  const b = e.currentTarget, box = document.getElementById('dbResult');
  b.disabled = true; box.innerHTML = '<div class="alert alert-info"><i class="fa-solid fa-spinner fa-spin"></i><span>جاري الاختبار…</span></div>';
  try {
    const r = await fetch(@json(route('install.database.test')), { method: 'POST', body: new FormData(document.getElementById('dbForm')), headers: { Accept: 'application/json' } });
    const j = await r.json();
    const msg = j.message || (j.errors ? Object.values(j.errors)[0][0] : 'تحقق من البيانات');
    box.innerHTML = `<div class="alert ${j.ok ? 'alert-ok' : 'alert-error'}"><i class="fa-solid ${j.ok ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i><span></span></div>`;
    box.querySelector('span').textContent = msg;
  } catch (err) { box.innerHTML = '<div class="alert alert-error"><span>تعذر الاختبار</span></div>'; }
  b.disabled = false;
});
</script>
@endpush
