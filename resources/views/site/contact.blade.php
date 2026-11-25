@extends('site.layout')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><i class="fa-solid fa-home" style="margin-left:4px;font-size:0.7rem;"></i> الرئيسية / تواصل معنا</div>
    <h1>{{ setting('contact_hero_title') }}</h1>
  </div>
</section>

@if($branches->count())
<section class="section soft contact-branches-section">
  <div class="container">
    <div class="section-head compact-head" data-reveal="">
      <div>
        <div class="section-label"><i class="fa-solid fa-map-location-dot" style="margin-left:6px;"></i> {{ setting('contact_branches_label') }}</div>
        <h2 class="section-title">{{ setting('contact_branches_title') }}</h2>
      </div>
    </div>
    <div class="branches-grid" data-reveal="">
      @foreach($branches as $b)
      <div class="branch-card">
        <i class="{{ fa($b->icon) }}"></i>
        <h3>{{ $b->title }}</h3>
        @if($b->phone)<a dir="ltr" href="tel:{{ $b->phone_link }}">{{ $b->phone_display }}</a>@endif
        @if($b->address)<p>{{ $b->address }}</p>@endif
        @if($b->whatsapp_number)<a class="branch-whatsapp-btn" href="https://wa.me/{{ $b->whatsapp_number }}" rel="noopener" target="_blank"><i class="fa-brands fa-whatsapp"></i> واتساب</a>@endif
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="section">
  <div class="container contact-layout">
    <form class="contact-form" data-reveal="" id="contactForm" action="{{ route('site.lead') }}" data-whatsapp="{{ preg_replace('/\D+/', '', setting('contact_form_whatsapp')) }}" data-intro="مرحبا، أريد الاستفسار عن خدمات {{ setting('site_name') }}" data-success="{{ setting('contact_form_success') }}">
      @csrf
      <div class="section-label"><i class="fa-solid fa-pen-to-square" style="margin-left:6px;"></i> {{ setting('contact_form_label') }}</div>
      <h2 class="section-title">{{ setting('contact_form_title') }}</h2>
      <div class="form-grid">
        <div class="field">
          <label for="name"><i class="fa-solid fa-user" style="margin-left:4px;font-size:0.75rem;color:var(--primary);"></i> الاسم</label>
          <input id="name" name="name" placeholder="اكتب اسمك" required type="text" maxlength="120" />
        </div>
        <div class="field">
          <label for="phone"><i class="fa-solid fa-phone" style="margin-left:4px;font-size:0.75rem;color:var(--primary);"></i> رقم الجوال</label>
          <input dir="ltr" id="phone" name="phone" placeholder="+966 5X XXX XXXX" required type="tel" maxlength="40" />
        </div>
        <div class="field">
          <label for="service"><i class="fa-solid fa-wrench" style="margin-left:4px;font-size:0.75rem;color:var(--primary);"></i> نوع الخدمة</label>
          <select id="service" name="service">
            @foreach($services as $s)<option>{{ $s->title }}</option>@endforeach
          </select>
        </div>
        <div class="field">
          <label for="space"><i class="fa-solid fa-building" style="margin-left:4px;font-size:0.75rem;color:var(--primary);"></i> نوع المساحة</label>
          <select id="space" name="space">
            @foreach(\App\Models\Setting::list('contact_space_options') as $opt)<option>{{ $opt }}</option>@endforeach
          </select>
        </div>
        <div class="field full">
          <label for="message"><i class="fa-solid fa-comment-dots" style="margin-left:4px;font-size:0.75rem;color:var(--primary);"></i> وصف مختصر</label>
          <textarea id="message" name="message" placeholder="اكتب ما تحتاجه في المساحة..." rows="5" maxlength="3000"></textarea>
        </div>
        <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;opacity:0" aria-hidden="true">
      </div>
      <button class="btn btn-primary contact-btn" type="submit"><i class="fa-solid fa-message" style="margin-left:6px;"></i> {{ setting('contact_form_button') }}</button>
      <p class="contact-form-status" role="status" style="margin-top:12px;font-size:.9rem;color:var(--primary-dark);display:none"></p>
    </form>

    <div class="contact-cards contact-info-frame" data-reveal="">
      @foreach($branches as $i => $b)
        @if($b->phone)
        <div class="contact-card">
          <i class="fa-solid {{ $i === 1 ? 'fa-phone-volume' : ($i > 1 ? fa($b->icon) : 'fa-phone') }}"></i>
          <span><small>{{ $b->title }}</small><strong dir="ltr">{{ $b->phone_display }}</strong></span>
        </div>
        @endif
      @endforeach
      @if(setting('email'))
      <div class="contact-card">
        <i class="fa-solid fa-envelope"></i>
        <span><small>البريد الإلكتروني</small><strong>{{ setting('email') }}</strong></span>
      </div>
      @endif
      @foreach($branches as $b)
        @if($b->map_src)
        <div class="map-card real-map-card">
          <div class="map-card-head"><i class="fa-solid fa-location-dot"></i><div><strong>{{ $b->map_title ?: $b->title . ' على الخريطة' }}</strong></div></div>
          <iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="{{ $b->map_src }}" title="خريطة {{ $b->title }} - {{ setting('brand_name') }}"></iframe>
        </div>
        @endif
      @endforeach
      <div class="map-card contact-more-card">
        <div>
          <i class="fa-solid fa-circle-info" style="font-size:1.5rem;color:var(--primary);margin-bottom:8px;"></i>
          <p>{{ setting('contact_more_text') }}</p>
          <a class="btn btn-secondary contact-more-btn" href="{{ url('/about') }}"><i class="fa-solid fa-arrow-left"></i> {{ setting('contact_more_btn') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
