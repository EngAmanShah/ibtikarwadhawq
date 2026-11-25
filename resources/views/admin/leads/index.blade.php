@extends('admin.layouts.app')
@section('title', 'طلبات العملاء')
@section('crumbs')<strong>طلبات العملاء</strong>@endsection

@section('content')
<div class="page-head rise">
  <div class="page-head-icon"><i class="fa-solid fa-inbox"></i></div>
  <div><h1>طلبات العملاء</h1><p>كل طلب يُرسل من نموذج «تواصل معنا» يُحفظ هنا تلقائيًا. تابع حالته وتواصل مع العميل بضغطة.</p></div>
  <div class="page-head-actions"><a class="btn btn-ghost" href="{{ route('admin.leads.export') }}"><i class="fa-solid fa-file-excel"></i> تنزيل Excel</a></div>
</div>

<div class="toolbar">
  <nav class="tabs">
    <a href="{{ route('admin.leads.index') }}" class="{{ ! $status ? 'active' : '' }}">الكل <b>{{ $counts->sum() }}</b></a>
    @foreach(\App\Models\Lead::STATUSES as $k => $s)
      <a href="{{ route('admin.leads.index', ['status' => $k]) }}" class="{{ $status === $k ? 'active' : '' }}"><i class="fa-solid {{ $s['icon'] }}"></i> {{ $s['label'] }} <b>{{ $counts[$k] ?? 0 }}</b></a>
    @endforeach
  </nav>
  <form class="input-wrap search" method="get"><i class="fa-solid fa-magnifying-glass"></i><input class="input" name="q" value="{{ $q }}" placeholder="بحث بالاسم أو الجوال…">@if($status)<input type="hidden" name="status" value="{{ $status }}">@endif</form>
</div>

<div class="card rise">
  @if($leads->isEmpty())
    <div class="empty"><div class="empty-art"><span></span><span><i class="fa-solid fa-inbox"></i></span></div><h3>لا توجد طلبات هنا</h3><p>عندما يرسل عميل طلبًا من الموقع سيظهر في هذه القائمة.</p></div>
  @else
  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>العميل</th><th>الخدمة</th><th>نوع المساحة</th><th>الحالة</th><th>التاريخ</th><th></th></tr></thead>
      <tbody>
        @foreach($leads as $l)
        <tr class="{{ $l->status === 'new' ? 'unread' : '' }}">
          <td><a class="who" href="{{ route('admin.leads.show', $l) }}"><span class="avatar">{{ mb_substr($l->name, 0, 1) }}</span><span><strong>{{ $l->name }}</strong><br><small dir="ltr" style="color:var(--muted)">{{ $l->phone }}</small></span></a></td>
          <td>{{ $l->service ?: '—' }}</td>
          <td>{{ $l->space ?: '—' }}</td>
          <td><span class="tag {{ $l->statusInfo()['tone'] }}"><i class="fa-solid {{ $l->statusInfo()['icon'] }}"></i> {{ $l->statusInfo()['label'] }}</span></td>
          <td><small title="{{ $l->created_at }}">{{ $l->created_at->diffForHumans() }}</small></td>
          <td style="white-space:nowrap">
            <a class="btn btn-soft btn-sm" href="{{ $l->whatsapp_link }}" target="_blank" title="مراسلة واتساب"><i class="fa-brands fa-whatsapp"></i></a>
            <a class="btn btn-ghost btn-sm" href="{{ route('admin.leads.show', $l) }}">فتح</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{ $leads->links() }}
  @endif
</div>
@endsection
