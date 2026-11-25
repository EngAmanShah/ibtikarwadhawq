<article class="scope-card" data-reveal="">
  <img alt="{{ $s->title }}" src="{{ media($s->card_image) }}" />
  <div class="scope-card-content">
    <i class="{{ fa($s->icon) }}"></i>
    <h3>{{ $s->title }}</h3>
    <p>{{ $s->excerpt }}</p>
    <a class="card-hover-link service-card-btn" href="{{ url('/services/' . $s->slug) }}">{{ setting('services_card_btn') }} <i class="fa-solid fa-arrow-left"></i></a>
  </div>
</article>
