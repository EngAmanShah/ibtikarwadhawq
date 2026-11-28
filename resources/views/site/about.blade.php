@extends('site.layout')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><i class="fa-solid fa-home" style="margin-left:4px;font-size:0.7rem;"></i> الرئيسية / عن {{ setting('brand_name') }}</div>
    <h1>{{ setting('about_hero_title') }}</h1>
    <p>{{ setting('about_hero_text') }}</p>
  </div>
</section>

<section class="section">
  <div class="container about-profile equal-frame-grid">
    <div class="about-profile-copy about-frame equal-frame-card" data-reveal="">
      <div class="section-label"><i class="fa-solid fa-building" style="margin-left:6px;"></i> {{ setting('about_intro_label') }}</div>
      <h2 class="section-title">{{ setting('about_intro_title') }}</h2>
      @if(setting('about_intro_text1'))<p>{{ setting('about_intro_text1') }}</p>@endif
      @if(setting('about_intro_text2'))<p>{{ setting('about_intro_text2') }}</p>@endif
    </div>
    <div class="about-profile-image about-img equal-frame-media" data-reveal="">
      <img alt="{{ setting('site_name') }}" loading="lazy" decoding="async" src="{{ media(setting('about_intro_image')) }}"@if($d = media_dimensions(setting('about_intro_image'))) width="{{ $d['width'] }}" height="{{ $d['height'] }}"@endif{!! img_srcset(setting('about_intro_image')) !!} sizes="(max-width: 900px) 92vw, 747px" />
    </div>
  </div>
</section>

@if(\App\Models\Setting::enabled('about_show_team'))
<section class="section soft about-team-section">
  <div class="container about-profile equal-frame-grid about-profile--reverse">
    <div class="about-profile-image about-img equal-frame-media team-image-frame" data-reveal="">
      <img alt="{{ setting('about_team_title') }}" loading="lazy" decoding="async" src="{{ media(setting('about_team_image')) }}"@if($d = media_dimensions(setting('about_team_image'))) width="{{ $d['width'] }}" height="{{ $d['height'] }}"@endif{!! img_srcset(setting('about_team_image')) !!} sizes="(max-width: 900px) 92vw, 747px" />
    </div>
    <div class="about-profile-copy about-frame equal-frame-card" data-reveal="">
      <div class="section-label"><i class="fa-solid fa-helmet-safety" style="margin-left:6px;"></i> {{ setting('about_team_label') }}</div>
      <h2 class="section-title">{{ setting('about_team_title') }}</h2>
      @if(setting('about_team_text1'))<p>{{ setting('about_team_text1') }}</p>@endif
      @if(setting('about_team_text2'))<p>{{ setting('about_team_text2') }}</p>@endif
    </div>
  </div>
</section>
@endif

@if($features->count())
<section class="section">
  <div class="container about-icon-grid">
    @foreach($features as $f)
      <div class="about-icon-card" data-reveal=""><i class="{{ fa($f->icon) }}"></i><h3>{{ $f->title }}</h3><p>{{ $f->text }}</p></div>
    @endforeach
  </div>
</section>
@endif

@if($cards->count())
<section class="section soft">
  <div class="container about-detail-split">
    @foreach($cards as $c)
      <div class="about-detail-card" data-reveal="">
        <span><i class="{{ fa($c->icon) }}" style="margin-left:4px;"></i> {{ $c->label }}</span>
        <h3>{{ $c->title }}</h3>
        <p>{{ $c->text }}</p>
      </div>
    @endforeach
  </div>
</section>
@endif

@if($values->count())
<section class="section">
  <div class="container">
    <div class="section-head" data-reveal="">
      <div>
        <div class="section-label"><i class="fa-solid fa-heart" style="margin-left:6px;"></i> {{ setting('about_values_label') }}</div>
        <h2 class="section-title">{{ setting('about_values_title') }}</h2>
      </div>
    </div>
    <div class="values-grid about-values-enhanced" data-reveal="">
      @foreach($values as $v)<span><i class="{{ fa($v->icon) }}"></i> {{ $v->title }}</span>@endforeach
    </div>
  </div>
</section>
@endif
@endsection
