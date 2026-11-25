@extends('site.layout')

@section('content')
<section class="sp-hero service-category-hero">
  <img alt="{{ $service->title }}" src="{{ media($service->hero_image ?: $service->card_image) }}" />
  <div class="sp-hero-overlay"></div>
  <div class="container">
    <div class="sp-hero-body">
      <nav class="sp-breadcrumb">
        <a href="{{ url('/') }}">الرئيسية</a>
        <i class="fa-solid fa-chevron-left"></i>
        <a href="{{ url('/services') }}">نطاق الأعمال</a>
        <i class="fa-solid fa-chevron-left"></i>
        <span>{{ $service->title }}</span>
      </nav>
      <h1>{{ $service->hero_title ?: $service->title }}</h1>
      @if($service->hero_text)<p>{{ $service->hero_text }}</p>@endif
      <div class="sp-hero-actions">
        <a class="sp-btn-primary" href="{{ url('/contact') }}"><i class="fa-solid fa-paper-plane"></i> {{ setting('service_btn_consult') }}</a>
        <a class="sp-btn-ghost" href="{{ url('/services') }}"><i class="fa-solid fa-border-all"></i> {{ setting('service_btn_all') }}</a>
      </div>
    </div>
  </div>
</section>

<section class="section service-category-content">
  @foreach($service->items as $i => $item)
  <section class="service-detail-split{{ $i % 2 ? ' service-detail-split--alt' : '' }}" data-reveal="">
    <div class="service-detail-text equal-frame-card">
      <div class="section-label"><i class="{{ fa($item->icon) }}" style="margin-left:6px;"></i> {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</div>
      <h2>{{ $item->title }}</h2>
      @if(! empty($item->points))
      <ul class="service-points">
        @foreach($item->points as $p)<li>{{ $p }}</li>@endforeach
      </ul>
      @endif
    </div>
    <div class="service-detail-media equal-frame-media">
      <img alt="{{ $item->title }}" loading="lazy" src="{{ media($item->image) }}" />
    </div>
  </section>
  @endforeach
</section>

@if($service->cta_text)
<section class="sp-contact-strip">
  <div class="container">
    <p>{{ $service->cta_text }}</p>
    <a class="sp-btn-primary" href="{{ url('/contact') }}"><i class="fa-solid fa-comment-dots"></i> {{ setting('service_strip_btn') }}</a>
  </div>
</section>
@endif
@endsection
