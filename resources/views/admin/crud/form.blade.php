@extends('admin.layouts.app')
@php
    $isEdit = (bool) $item;
    $tf = $meta['titleField'];
    $heading = $isEdit ? 'تعديل: ' . $item->$tf : 'إضافة ' . $meta['singular'] . ' جديد';
    $parent = $meta['parent'] ?? null;
    $preview = $item instanceof \App\Models\Service ? '/services/' . $item->slug : ($meta['preview'] ?? '/');
@endphp
@section('title', $heading)
@section('crumbs')
  @if($parent)<a href="{{ route('admin.services.index') }}">الخدمات</a><i class="fa-solid fa-chevron-left"></i><a href="{{ route('admin.services.edit', $parent) }}">{{ $parent->title }}</a>
  @else<a href="{{ $meta['index'] }}">{{ $meta['title'] }}</a>@endif
  <i class="fa-solid fa-chevron-left"></i><strong>{{ $isEdit ? $item->$tf : 'جديد' }}</strong>
@endsection
@section('preview', url($preview))

@section('content')
<div class="page-head rise">
  <div class="page-head-icon"><i class="fa-solid {{ $isEdit ? 'fa-pen' : 'fa-plus' }}"></i></div>
  <div>
    <h1>{{ $heading }}</h1>
    <p>{{ $parent ? 'قسم ضمن صفحة «' . $parent->title . '»' : $meta['title'] }}</p>
  </div>
  <div class="page-head-actions">
    <a class="btn btn-ghost" href="{{ $meta['index'] }}"><i class="fa-solid fa-arrow-right"></i> رجوع</a>
    @if($isEdit && $item instanceof \App\Models\Service)<a class="btn btn-ghost" href="{{ url($preview) }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> صفحة الخدمة</a>@endif
    <button class="btn btn-gold" form="crudForm" data-loading><span class="spinner"></span><i class="fa-solid fa-floppy-disk btn-label-ico"></i> {{ $isEdit ? 'حفظ التعديلات' : 'إضافة ونشر' }}</button>
  </div>
</div>

<form id="crudForm" class="card rise" method="post" action="{{ $isEdit ? $meta['update']($item) : $meta['store'] }}" enctype="multipart/form-data" data-track>
  @csrf @if($isEdit) @method('put') @endif
  <div class="card-head"><span class="chip-ico"><i class="fa-solid {{ $meta['icon'] }}"></i></span><div><h2>بيانات {{ $meta['singular'] }}</h2><small>الحقول المعلّمة بـ <span style="color:var(--red)">*</span> مطلوبة</small></div></div>
  <div class="card-body">
    <div class="fields">
      @foreach($fields as $f)
        @php
          $v = $item ? $item->{$f['key']} : null;
          if ($v instanceof \Carbon\CarbonInterface) $v = $v->format('Y-m-d');
          if (is_bool($v)) $v = $v ? '1' : '0';
        @endphp
        @include('admin.partials.field', ['f' => $f, 'value' => $v])
      @endforeach
    </div>
  </div>
</form>

@if($isEdit && $item instanceof \App\Models\Service)
  @php
    $im = ['title' => 'أقسام الخدمة', 'singular' => 'قسم', 'icon' => 'fa-list-check', 'layout' => 'grid', 'image' => 'image', 'titleField' => 'title', 'sub' => null, 'contain' => false, 'type' => 'items',
      'edit' => fn ($i) => route('admin.services.items.edit', $i), 'destroy' => fn ($i) => route('admin.services.items.destroy', $i)];
  @endphp
  <div class="page-head" id="items" style="margin-top:10px">
    <div><h1 style="font-size:20px"><i class="fa-solid fa-list-check" style="color:var(--gold)"></i> أقسام صفحة الخدمة</h1><p>كل قسم يظهر في صفحة الخدمة بصورة ونقاط. اسحب لتغيير الترتيب.</p></div>
    <div class="page-head-actions"><a class="btn btn-gold" href="{{ route('admin.services.items.create', $item) }}"><i class="fa-solid fa-plus"></i> إضافة قسم</a></div>
  </div>
  @if($item->items->isEmpty())
    <div class="card empty"><div class="empty-art"><span></span><span><i class="fa-solid fa-list-check"></i></span></div><h3>لا توجد أقسام بعد</h3><p>أضف أقسامًا لتظهر تفاصيل الخدمة.</p></div>
  @else
    @include('admin.crud.list', ['items' => $item->items, 'meta' => $im])
  @endif
@endif

@include('admin.partials.save-dock')
@endsection
