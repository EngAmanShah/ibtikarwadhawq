@extends('admin.layouts.app')
@section('title', $meta['title'])
@section('crumbs')<strong>{{ $meta['title'] }}</strong>@endsection
@section('preview', url($meta['preview'] ?? '/'))

@section('content')
<div class="page-head rise">
  <div class="page-head-icon"><i class="fa-solid {{ $meta['icon'] }}"></i></div>
  <div>
    <h1>{{ $meta['title'] }}</h1>
    <p>{{ $meta['description'] }}</p>
  </div>
  <div class="page-head-actions">
    <a class="btn btn-gold" href="{{ $meta['create'] }}"><i class="fa-solid fa-plus"></i> إضافة {{ $meta['singular'] }}</a>
  </div>
</div>

@if($items->isEmpty())
  <div class="card empty">
    <div class="empty-art"><span></span><span><i class="fa-solid {{ $meta['icon'] }}"></i></span></div>
    <h3>لا توجد عناصر بعد</h3>
    <p>ابدأ بإضافة أول {{ $meta['singular'] }} وسيظهر في الموقع فورًا.</p>
    <a class="btn btn-gold" href="{{ $meta['create'] }}"><i class="fa-solid fa-plus"></i> إضافة {{ $meta['singular'] }}</a>
  </div>
@else
  <div class="toolbar">
    <div class="input-wrap search"><i class="fa-solid fa-magnifying-glass"></i><input class="input" placeholder="ابحث في {{ $meta['title'] }}…" data-filter="#list"></div>
    <span class="tag gold"><i class="fa-solid fa-hand-pointer"></i> اسحب من <i class="fa-solid fa-grip-vertical"></i> لتغيير الترتيب</span>
    <span class="tag">{{ $items->count() }} عنصر</span>
  </div>
  @include('admin.crud.list', ['items' => $items, 'meta' => $meta])
@endif
@endsection
