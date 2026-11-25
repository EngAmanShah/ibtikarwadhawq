@if ($paginator->hasPages())
<nav class="pager" aria-label="التنقل بين الصفحات">
  @if ($paginator->onFirstPage())<span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>@else<a href="{{ $paginator->previousPageUrl() }}" aria-label="السابق"><i class="fa-solid fa-chevron-right"></i></a>@endif
  @foreach ($elements as $element)
    @if (is_string($element))<span class="disabled">{{ $element }}</span>@endif
    @if (is_array($element))
      @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())<span class="current">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
      @endforeach
    @endif
  @endforeach
  @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}" aria-label="التالي"><i class="fa-solid fa-chevron-left"></i></a>@else<span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>@endif
</nav>
@endif
