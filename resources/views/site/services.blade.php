@extends('site.layout')

@section('content')
<section class="page-hero">
<section class="section soft">
  <div class="container">
    <div class="section-head" data-reveal="">
      <div>
        <div class="section-label"><i class="fa-solid fa-briefcase" style="margin-left:6px;"></i> {{ setting('services_label') }}</div>
        <h2 class="section-title">{{ setting('services_title') }}</h2>
        <p class="section-desc">{{ setting('services_desc') }}</p>
      </div>
    </div>
    <div class="scope-grid main-services-grid">
      @foreach($services as $s)
        @include('site.partials.service-card', ['s' => $s])
      @endforeach
    </div>
  </div>
</section>
</section>
@endsection
