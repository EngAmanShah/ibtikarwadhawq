@extends('site.layout')

@section('content')
<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><i class="fa-solid fa-home" style="margin-left:4px;font-size:0.7rem;"></i> الرئيسية / الوثائق الرسمية</div>
    <h1>{{ setting('documents_hero_title') }}</h1>
    <p>{{ setting('documents_hero_text') }}</p>
  </div>
</section>
<section class="section soft">
  <div class="container">
    @if($facts->count())
    <div class="info-strip" data-reveal="">
      @foreach($facts as $f)
        <div><strong><i class="{{ fa($f->icon) }}" style="margin-left:4px;"></i> {{ $f->label }}</strong>{{ $f->title }}</div>
      @endforeach
    </div>
    @endif
    <div class="docs-grid">
      @foreach($documents as $d)
      <article class="doc-card" data-reveal="">
        @if($d->preview_image)<div class="doc-preview"><img alt="معاينة {{ $d->title }}" loading="lazy" src="{{ media($d->preview_image) }}" /></div>@endif
        <i class="fa-solid fa-file-pdf"></i>
        <h2>{{ $d->title }}</h2>
        @if($d->file)<a href="{{ media($d->file) }}" target="_blank"><i class="fa-solid fa-external-link-alt" style="margin-left:4px;"></i> {{ setting('documents_btn') }}</a>@endif
      </article>
      @endforeach
    </div>
  </div>
</section>
@endsection
