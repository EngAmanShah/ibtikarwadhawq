@php $cardDims = media_dimensions($s->card_image); @endphp
<article class="scope-card" data-reveal="">
  <img alt="{{ $s->title }} — {{ setting('brand_name') }}" src="{{ media($s->card_image) }}"@if($cardDims) width="{{ $cardDims['width'] }}" height="{{ $cardDims['height'] }}"@endif loading="lazy" decoding="async"{!! img_srcset($s->card_image) !!} sizes="(max-width: 900px) 92vw, 380px" />
  <div class="scope-card-content">
    <i class="{{ fa($s->icon) }}"></i>
    <h3>{{ $s->title }}</h3>
    <p>{{ $s->excerpt }}</p>
    <a class="card-hover-link service-card-btn" href="{{ url('/services/' . $s->slug) }}">{{ setting('services_card_btn') }}<span class="sr-only"> {{ $s->title }}</span> <i class="fa-solid fa-arrow-left"></i></a>
  </div>
</article>
