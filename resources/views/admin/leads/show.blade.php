@extends('admin.layouts.app')
@section('title', 'طلب: ' . $lead->name)
@section('crumbs')<a href="{{ route('admin.leads.index') }}">طلبات العملاء</a><i class="fa-solid fa-chevron-left"></i><strong>{{ $lead->name }}</strong>@endsection

@section('content')
<div class="page-head rise">
  <div class="avatar" style="width:58px;height:58px;font-size:22px;border-radius:18px">{{ mb_substr($lead->name, 0, 1) }}</div>
  <div><h1>{{ $lead->name }}</h1><p>وصل {{ $lead->created_at->diffForHumans() }} — {{ $lead->created_at->format('Y/m/d h:i A') }}</p></div>
  <div class="page-head-actions">
    <a class="btn btn-gold" href="{{ $lead->whatsapp_link }}" target="_blank"><i class="fa-brands fa-whatsapp"></i> مراسلة واتساب</a>
    <a class="btn btn-ghost" href="tel:{{ $lead->phone }}"><i class="fa-solid fa-phone"></i> اتصال</a>
  </div>
</div>

<div class="lead-view rise">
  <div class="card">
    <div class="card-head"><span class="chip-ico"><i class="fa-solid fa-id-card"></i></span><h3>تفاصيل الطلب</h3></div>
    <div class="card-body">
      <dl class="info-list">
        <div><dt><i class="fa-solid fa-user"></i> الاسم</dt><dd>{{ $lead->name }}</dd></div>
        <div><dt><i class="fa-solid fa-phone"></i> الجوال</dt><dd dir="ltr" style="text-align:right">{{ $lead->phone }}</dd></div>
        <div><dt><i class="fa-solid fa-wrench"></i> الخدمة</dt><dd>{{ $lead->service ?: '—' }}</dd></div>
        <div><dt><i class="fa-solid fa-building"></i> نوع المساحة</dt><dd>{{ $lead->space ?: '—' }}</dd></div>
        <div><dt><i class="fa-solid fa-comment-dots"></i> التفاصيل</dt><dd style="white-space:pre-line">{{ $lead->message ?: '—' }}</dd></div>
      </dl>
    </div>
  </div>
  <form class="card" method="post" action="{{ route('admin.leads.update', $lead) }}">
    @csrf @method('put')
    <div class="card-head"><span class="chip-ico"><i class="fa-solid fa-flag"></i></span><h3>المتابعة</h3></div>
    <div class="card-body" style="display:flex;flex-direction:column;gap:16px">
      <div class="status-pick">
        @foreach(\App\Models\Lead::STATUSES as $k => $s)
          <label><input type="radio" name="status" value="{{ $k }}" @checked($lead->status === $k)><span><i class="fa-solid {{ $s['icon'] }}"></i> {{ $s['label'] }}</span></label>
        @endforeach
      </div>
      <div class="field"><label for="notes">ملاحظات داخلية (لا يراها العميل)</label><textarea id="notes" name="notes" class="textarea" rows="4" placeholder="مثال: تم الاتصال وتحديد موعد معاينة يوم الأحد">{{ $lead->notes }}</textarea></div>
      <button class="btn btn-gold" data-loading><span class="spinner"></span><i class="fa-solid fa-floppy-disk btn-label-ico"></i> حفظ</button>
    </div>
  </form>
</div>
<form method="post" action="{{ route('admin.leads.destroy', $lead) }}" data-confirm="حذف هذا الطلب؟" style="margin-top:6px">@csrf @method('delete')<button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i> حذف الطلب</button></form>
@endsection
