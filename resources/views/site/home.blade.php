@extends('site.layout')

@push('head')
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" rel="stylesheet" />
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
      <div class="material-card primary"><img alt="{{ setting('home_hero_title') }}" src="{{ media(setting('home_hero_image1')) }}" /></div>
      <div class="material-card secondary"><img alt="{{ setting('brand_name') }}" src="{{ media(setting('home_hero_image2')) }}" /></div>
      <div class="material-card third"><img alt="واجهة {{ setting('brand_name') }}" src="{{ media(setting('home_hero_image3')) }}" /></div>
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
      <img alt="{{ setting('site_name') }}" loading="lazy" src="{{ media(setting('home_about_image')) }}" />
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
        <div class="finish-card"><img alt="{{ $slide->title }}" src="{{ media($slide->image) }}" /><h3>{{ $slide->title }}</h3></div>
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
          <img alt="{{ $c->name }}" loading="lazy" src="{{ media($c->logo) }}" />
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
    <img alt="{{ setting('home_details_label') }}" src="{{ media(setting('home_details_image')) }}" />
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
@endpush
