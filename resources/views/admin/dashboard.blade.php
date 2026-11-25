@extends('admin.layouts.app')
@section('title', 'نظرة عامة')
@section('crumbs')<strong>نظرة عامة</strong>@endsection

@php
  $max = max(1, $chart->max('v'));
  $diff = $prevWeek ? round(($week - $prevWeek) / $prevWeek * 100) : null;
  $circ = 2 * M_PI * 64;
  $months = ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
  $daysAr = ['الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
  $pageNames = ['/' => 'الرئيسية', '/about' => 'من نحن', '/services' => 'الخدمات', '/documents' => 'الوثائق', '/contact' => 'تواصل معنا'];
@endphp

@section('content')
<div class="rise" style="display:flex;flex-direction:column;gap:20px">
  <section class="hello">
    <div>
      <div class="date"><i class="fa-regular fa-calendar"></i> {{ $daysAr[now()->dayOfWeek] }}، {{ now()->day }} {{ $months[now()->month - 1] }} {{ now()->year }}</div>
      <h1>{{ ar_greeting() }}، <span>{{ auth()->user()->name }}</span></h1>
      <p>كل شيء في موقعك تحت يدك. عدّل أي نص أو صورة وسيظهر التغيير للزوار فورًا — بدون أي برمجة.</p>
      <div class="hello-actions">
        <a class="btn btn-gold" href="{{ route('admin.pages.edit', 'home') }}"><i class="fa-solid fa-pen-ruler"></i> تعديل الصفحة الرئيسية</a>
        <button class="btn btn-ghost" data-palette type="button"><i class="fa-solid fa-magnifying-glass"></i> ماذا تريد أن تعدّل؟</button>
      </div>
    </div>
    <div class="health-ring" title="جودة الموقع">
      <svg viewBox="0 0 150 150"><circle cx="75" cy="75" r="64" fill="none" stroke="#ece5da" stroke-width="11"/><circle cx="75" cy="75" r="64" fill="none" stroke="#1f1b17" stroke-width="11" stroke-linecap="butt" stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $circ * (1 - $score / 100) }}"/></svg>
      <div><strong>{{ $score }}%</strong><small>جاهزية الموقع</small></div>
    </div>
  </section>

  <div class="bento">
    <div class="card stat span-3"><div class="stat-top"><i class="fa-solid fa-eye"></i> زيارات اليوم</div><div class="stat-value">{{ number_format($today) }}</div><div class="stat-sub">آخر 7 أيام: {{ number_format($week) }} @if($diff !== null)<span class="{{ $diff >= 0 ? 'up' : 'down' }}"><i class="fa-solid fa-arrow-{{ $diff >= 0 ? 'up' : 'down' }}"></i> {{ abs($diff) }}%</span>@endif</div></div>
    <div class="card stat span-3"><a class="stretched" href="{{ route('admin.leads.index', ['status' => 'new']) }}"></a><div class="stat-top"><i class="fa-solid fa-inbox"></i> طلبات جديدة</div><div class="stat-value">{{ $leadsNew }}</div><div class="stat-sub">هذا الشهر: {{ $leadsMonth }} طلب</div></div>
    <div class="card stat span-3"><a class="stretched" href="{{ route('admin.services.index') }}"></a><div class="stat-top"><i class="fa-solid fa-layer-group"></i> الخدمات</div><div class="stat-value">{{ $counts['services'] }}</div><div class="stat-sub">{{ $counts['slides'] }} صورة في شريط التشطيبات</div></div>
    <div class="card stat span-3"><a class="stretched" href="{{ route('admin.clients.index') }}"></a><div class="stat-top"><i class="fa-solid fa-handshake"></i> العملاء والوثائق</div><div class="stat-value">{{ $counts['clients'] }}</div><div class="stat-sub">{{ $counts['documents'] }} وثيقة رسمية</div></div>

    <div class="card span-8">
      <div class="card-head"><span class="chip-ico"><i class="fa-solid fa-chart-column"></i></span><div><h3>زيارات الموقع</h3><small>آخر 14 يومًا (بدون زياراتك وبدون محركات البحث)</small></div></div>
      <div class="chart-wrap chart">
        <svg viewBox="0 0 700 190" preserveAspectRatio="none" role="img" aria-label="رسم الزيارات">
          <defs><linearGradient id="barGrad" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="#bfa27e"/><stop offset="1" stop-color="#e9dcc8"/></linearGradient></defs>
          @foreach([0, .5, 1] as $g)<line class="grid" x1="0" x2="700" y1="{{ 160 - 150 * $g }}" y2="{{ 160 - 150 * $g }}"/>@endforeach
          @foreach($chart->values() as $i => $p)
            @php $h = max(3, 150 * $p['v'] / $max); $x = 700 - ($i + 1) * 50 + 9; @endphp
            <rect class="bar" x="{{ $x }}" y="{{ 160 - $h }}" width="32" height="{{ $h }}" rx="8"><title>{{ $p['day'] }}: {{ $p['v'] }} زيارة</title></rect>
            <text class="axis" x="{{ $x + 16 }}" y="182" text-anchor="middle">{{ \Carbon\Carbon::parse($p['day'])->format('d/m') }}</text>
          @endforeach
        </svg>
      </div>
    </div>

    <div class="card span-4">
      <div class="card-head"><span class="chip-ico"><i class="fa-solid fa-list-check"></i></span><div><h3>جاهزية الموقع</h3><small>نقاط تحتاج انتباهك</small></div></div>
      <div class="checklist">
        @foreach($checks as $c)
          <div class="check {{ $c['ok'] ? 'ok' : 'bad' }}"><i class="fa-solid {{ $c['ok'] ? 'fa-check' : 'fa-exclamation' }}"></i><span>{{ $c['label'] }}</span>@unless($c['ok'])<a href="{{ $c['url'] }}">إصلاح ←</a>@endunless</div>
        @endforeach
      </div>
    </div>

    <div class="card span-5">
      <div class="card-head"><span class="chip-ico"><i class="fa-solid fa-bolt"></i></span><div><h3>اختصارات سريعة</h3><small>أكثر ما تحتاجه يوميًا</small></div></div>
      <div class="quick-grid">
        <a class="quick" href="{{ route('admin.pages.edit', 'home') }}"><i class="fa-solid fa-house-chimney"></i><span><strong>الصفحة الرئيسية</strong><small>النصوص والصور</small></span></a>
        <a class="quick" href="{{ route('admin.services.index') }}"><i class="fa-solid fa-layer-group"></i><span><strong>الخدمات</strong><small>إضافة وتعديل</small></span></a>
        <a class="quick" href="{{ route('admin.branches.index') }}"><i class="fa-solid fa-phone"></i><span><strong>أرقام التواصل</strong><small>الفروع والواتساب</small></span></a>
        <a class="quick" href="{{ route('admin.clients.create') }}"><i class="fa-solid fa-handshake"></i><span><strong>إضافة عميل</strong><small>شعار جديد</small></span></a>
        <a class="quick" href="{{ route('admin.documents.index') }}"><i class="fa-solid fa-file-arrow-up"></i><span><strong>الوثائق</strong><small>رفع شهادة</small></span></a>
        <a class="quick" href="{{ route('admin.pages.edit', 'identity') }}"><i class="fa-solid fa-swatchbook"></i><span><strong>الشعار والألوان</strong><small>هوية الموقع</small></span></a>
      </div>
    </div>

    <div class="card span-7">
      <div class="card-head"><span class="chip-ico"><i class="fa-solid fa-inbox"></i></span><div><h3>أحدث طلبات العملاء</h3><small>من نموذج «تواصل معنا»</small></div><div class="card-head-end"><a class="btn btn-soft btn-sm" href="{{ route('admin.leads.index') }}">عرض الكل</a></div></div>
      @forelse($latestLeads as $l)
        <a class="lead-mini" href="{{ route('admin.leads.show', $l) }}">
          <span class="avatar">{{ mb_substr($l->name, 0, 1) }}</span>
          <span><strong>{{ $l->name }}</strong><small>{{ $l->service ?: 'بدون تحديد خدمة' }}</small></span>
          <span class="end"><span class="tag {{ $l->statusInfo()['tone'] }}">{{ $l->statusInfo()['label'] }}</span><small>{{ $l->created_at->diffForHumans() }}</small></span>
        </a>
      @empty
        <div class="empty" style="padding:30px"><p style="margin:0">لم تصل طلبات بعد — ستظهر هنا فور إرسالها من الموقع.</p></div>
      @endforelse
    </div>

    <div class="card span-6">
      <div class="card-head"><span class="chip-ico"><i class="fa-solid fa-fire"></i></span><div><h3>الصفحات الأكثر زيارة</h3><small>آخر 30 يومًا</small></div></div>
      <div class="feed">
        @forelse($topPages as $p)
          <div class="feed-item"><i class="fa-solid fa-file-lines"></i><span>{{ $pageNames[$p->path] ?? $p->path }}<small dir="ltr" style="text-align:right">{{ $p->path }}</small></span><span class="when"><b>{{ number_format($p->total) }}</b> زيارة</span></div>
        @empty
          <p class="help" style="padding:14px 0">لا توجد بيانات بعد.</p>
        @endforelse
      </div>
    </div>

    <div class="card span-6">
      <div class="card-head"><span class="chip-ico"><i class="fa-solid fa-clock-rotate-left"></i></span><div><h3>آخر التعديلات</h3><small>ماذا تغيّر في الموقع</small></div></div>
      <div class="feed">
        @forelse($activity as $a)
          <div class="feed-item"><i class="fa-solid {{ $a->icon ?: 'fa-pen' }}"></i><span>{{ $a->action }}: {{ $a->subject }}<small>{{ $a->user->name ?? '—' }}</small></span><span class="when">{{ $a->created_at->diffForHumans() }}</span></div>
        @empty
          <p class="help" style="padding:14px 0">لم تُجرَ تعديلات بعد.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
