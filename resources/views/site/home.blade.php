@extends('site.layout')

@php
    $hero1 = setting('home_hero_image1');
    $hero1Dims = media_dimensions($hero1);
    $hero1Srcset = srcset_list($hero1);
    $hero2 = setting('home_hero_image2');
    $hero2Dims = media_dimensions($hero2);
    $hero3 = setting('home_hero_image3');
    $hero3Dims = media_dimensions($hero3);
@endphp

@push('head')
<link href="{{ asset('assets/vendor/lib/slick.min.css') }}?v={{ @filemtime(public_path('assets/vendor/lib/slick.min.css')) }}" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" />
<noscript><link href="{{ asset('assets/vendor/lib/slick.min.css') }}" rel="stylesheet" /></noscript>
@if($hero1Srcset)
<link as="image" fetchpriority="high" href="{{ media($hero1) }}" imagesizes="(max-width: 768px) 92vw, 560px" imagesrcset="{{ $hero1Srcset }}" rel="preload" />
@endif
@endpush

@section('content')
<section class="hero">
  <div class="container hero-layout">
    <div class="hero-copy" data-reveal="">
      <span class="eyebrow">{{ setting('home_hero_eyebrow') }}</span>
      <h1>{{ setting('home_hero_title') }}</h1>
      <p>{{ setting('home_hero_text') }}</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="{{ url('/services') }}">{{ setting('home_hero_btn1') }} <i class="fa-solid fa-arrow-left"></i></a>
        <a class="btn btn-soft" href="{{ url('/contact') }}"><i class="fa-solid fa-comment-dots"></i> {{ setting('home_hero_btn2') }}</a>
      </div>
    </div>
    <div class="hero-board" data-reveal="">
      <div class="material-card primary"><img alt="{{ setting('home_hero_title') }} — {{ setting('brand_name') }}" decoding="async" fetchpriority="high" src="{{ media($hero1) }}"@if($hero1Dims) width="{{ $hero1Dims['width'] }}" height="{{ $hero1Dims['height'] }}"@endif{!! img_srcset($hero1) !!} sizes="(max-width: 768px) 92vw, 560px" /></div>
      <div class="material-card secondary"><img alt="{{ setting('brand_name') }}" decoding="async" src="{{ media($hero2) }}"@if($hero2Dims) width="{{ $hero2Dims['width'] }}" height="{{ $hero2Dims['height'] }}"@endif{!! img_srcset($hero2) !!} sizes="(max-width: 768px) 40vw, 250px" /></div>
      <div class="material-card third"><img alt="واجهة {{ setting('brand_name') }}" decoding="async" src="{{ media($hero3) }}"@if($hero3Dims) width="{{ $hero3Dims['width'] }}" height="{{ $hero3Dims['height'] }}"@endif{!! img_srcset($hero3) !!} sizes="(max-width: 768px) 34vw, 210px" /></div>
    </div>
  </div>
</section>

@if(\App\Models\Setting::enabled('home_show_about'))
<section class="section home-about-section">
  <div class="container about-profile equal-frame-grid">
    <div class="about-profile-copy about-frame equal-frame-card" data-reveal="">
      <div class="section-label"><i class="fa-solid fa-building" style="margin-left:6px"></i> {{ setting('home_about_label') }}</div>
      <h2 class="section-title">{{ setting('home_about_title') }}</h2>
      @if(setting('home_about_text1'))<p>{{ setting('home_about_text1') }}</p>@endif
      @if(setting('home_about_text2'))<p>{{ setting('home_about_text2') }}</p>@endif
      <div class="section-actions"><a class="btn btn-primary" href="{{ url('/about') }}">{{ setting('home_about_btn') }} <i class="fa-solid fa-arrow-left"></i></a></div>
    </div>
    <div class="about-profile-image about-img equal-frame-media" data-reveal="">
      <img alt="{{ setting('site_name') }}" loading="lazy" decoding="async" src="{{ media(setting('home_about_image')) }}"@if($d = media_dimensions(setting('home_about_image'))) width="{{ $d['width'] }}" height="{{ $d['height'] }}"@endif{!! img_srcset(setting('home_about_image')) !!} sizes="(max-width: 900px) 92vw, 747px" />
    </div>
  </div>
</section>
@endif

@if(\App\Models\Setting::enabled('home_show_services') && $services->count())
<section class="section soft">
  <div class="container">
    <div class="section-head" data-reveal="">
      <div>
        <div class="section-label"><i class="fa-solid fa-briefcase" style="margin-left:6px"></i> {{ setting('home_services_label') }}</div>
        <h2 class="section-title">{{ setting('home_services_title') }}</h2>
        <p class="section-desc">{{ setting('home_services_desc') }}</p>
      </div>
    </div>
    <div class="scope-grid main-services-grid">
      @foreach($services as $s)
        @include('site.partials.service-card', ['s' => $s])
      @endforeach
    </div>
  </div>
</section>
@endif

@if(\App\Models\Setting::enabled('home_show_finishing') && $slides->count())
<section class="section premium-finishing-section">
  <div class="container">
    <div class="section-head compact-head" data-reveal="">
      <div>
        <div class="section-label"><i class="fa-solid fa-star" style="margin-left:6px"></i> {{ setting('home_finishing_label') }}</div>
        <h2 class="section-title">{{ setting('home_finishing_title') }}</h2>
      </div>
    </div>
  </div>
  <div aria-label="{{ setting('home_finishing_label') }}" class="finishing-slider" dir="ltr">
    <div class="finishing-track">
      @foreach($slides as $slide)
        <div class="finish-card"><img alt="{{ $slide->title }}" loading="lazy" decoding="async" src="{{ media($slide->image) }}"@if($d = media_dimensions($slide->image)) width="{{ $d['width'] }}" height="{{ $d['height'] }}"@endif{!! img_srcset($slide->image) !!} sizes="(max-width: 768px) 45vw, 250px" /><h3>{{ $slide->title }}</h3></div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if(\App\Models\Setting::enabled('home_show_clients') && $clients->count())
<section class="section soft clients-section">
  <div class="container" data-reveal="">
    <div class="section-head compact-head">
      <div>
        <div class="section-label"><i class="fa-solid fa-handshake" style="margin-left:6px"></i> {{ setting('home_clients_label') }}</div>
        <h2 class="section-title">{{ setting('home_clients_title') }}</h2>
      </div>
    </div>
    <div class="clients-grid">
      @foreach($clients as $c)
        <div class="client-logo-wrap">
          @if($c->url)<a href="{{ $c->url }}" target="_blank" rel="noopener">@endif
          <img alt="{{ $c->name }}" loading="lazy" decoding="async" src="{{ media($c->logo) }}"@if($d = media_dimensions($c->logo)) width="{{ $d['width'] }}" height="{{ $d['height'] }}"@endif{!! img_srcset($c->logo) !!} sizes="(max-width: 768px) 30vw, 190px" />
          @if($c->url)</a>@endif
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if(\App\Models\Setting::enabled('home_show_details'))
<section class="section details-finishing">
  <div class="container detail-band" data-reveal="">
    <img alt="{{ setting('home_details_label') }}" loading="lazy" decoding="async" src="{{ media(setting('home_details_image')) }}"@if($d = media_dimensions(setting('home_details_image'))) width="{{ $d['width'] }}" height="{{ $d['height'] }}"@endif{!! img_srcset(setting('home_details_image')) !!} sizes="(max-width: 900px) 92vw, 817px" />
    <div class="detail-copy">
      <div class="section-label"><i class="fa-solid fa-eye" style="margin-left:6px"></i> {{ setting('home_details_label') }}</div>
      <h2 class="section-title">{{ setting('home_details_title') }}</h2>
      <p class="section-desc">{{ setting('home_details_desc') }}</p>
      <div class="detail-list">
        @foreach(\App\Models\Setting::list('home_details_points') as $point)
          <div><i class="fa-solid fa-check-circle"></i> {{ $point }}</div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif

@if(\App\Models\Setting::enabled('home_show_docs'))
<section class="section soft">
  <div class="container docs-row" data-reveal="">
    <div>
      <div class="section-label"><i class="fa-solid fa-file-certificate" style="margin-left:6px"></i> {{ setting('home_docs_label') }}</div>
      <h2 class="section-title">{{ setting('home_docs_title') }}</h2>
      <p class="section-desc">{{ setting('home_docs_desc') }}</p>
      <div class="section-actions" style="justify-content:center">
        <a class="btn btn-primary" href="{{ url('/documents') }}"><i class="fa-solid fa-folder-open" style="margin-left:6px"></i> {{ setting('home_docs_btn') }}</a>
      </div>
    </div>
  </div>
</section>
@endif
@endsection

@push('scripts')
<script defer src="{{ asset('assets/vendor/lib/jquery.min.js') }}?v={{ @filemtime(public_path('assets/vendor/lib/jquery.min.js')) }}"></script>
<script defer src="{{ asset('assets/vendor/lib/slick.min.js') }}?v={{ @filemtime(public_path('assets/vendor/lib/slick.min.js')) }}"></script>
@endpush
