@extends('admin.layouts.app')
@section('title', $page['title'])
@section('crumbs')<strong>{{ $page['title'] }}</strong>@endsection
@section('preview', url($page['preview'] ?? '/'))

@section('content')
<div class="page-head rise">
  <div class="page-head-icon"><i class="fa-solid {{ $page['icon'] }}"></i></div>
  <div>
    <h1>{{ $page['title'] }}</h1>
    <p>{{ $page['description'] }}</p>
  </div>
  <div class="page-head-actions">
    @if(! empty($page['preview']))
      <a class="btn btn-ghost" href="{{ url($page['preview']) }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> افتح الصفحة</a>
    @endif
    <button class="btn btn-gold" form="pageForm" data-loading><span class="spinner"></span><i class="fa-solid fa-floppy-disk btn-label-ico"></i> حفظ التعديلات</button>
  </div>
</div>

<div class="editor">
  <aside class="card editor-index">
    <div class="idx-title">أقسام الصفحة</div>
    @foreach($page['sections'] as $i => $section)
      <a href="#sec-{{ $i }}" class="{{ $i === 0 ? 'current' : '' }}"><i class="fa-solid {{ $section['icon'] }}"></i><span>{{ \Illuminate\Support\Str::limit(str_replace(['قسم ', '«', '»'], '', $section['title']), 26) }}</span></a>
    @endforeach
    @if(! empty($page['related']))
      <div class="idx-sep"></div>
      <div class="idx-title">محتوى مرتبط بالصفحة</div>
      @foreach($page['related'] as $rel)
        <a class="idx-related" href="{{ route($rel['route'], $rel['params'] ?? []) }}"><i class="fa-solid {{ $rel['icon'] }}"></i><span>{{ $rel['label'] }}</span></a>
      @endforeach
    @endif
  </aside>

  <form id="pageForm" class="editor-sections rise" method="post" action="{{ route('admin.pages.update', $page['key']) }}" enctype="multipart/form-data" data-track>
    @csrf @method('put')
    @foreach($page['sections'] as $i => $section)
      <section class="card section-card" id="sec-{{ $i }}">
        <div class="card-head">
          <span class="chip-ico"><i class="fa-solid {{ $section['icon'] }}"></i></span>
          <div><h2>{{ $section['title'] }}</h2></div>
          @if(! empty($section['toggle']))
            <div class="card-head-end">
              <label class="switch" title="إظهار أو إخفاء هذا القسم من الموقع">
                <input type="hidden" name="{{ $section['toggle'] }}" value="0">
                <input type="checkbox" name="{{ $section['toggle'] }}" value="1" data-section-toggle @checked(($values[$section['toggle']] ?? '1') === '1')>
                <span class="track"></span>
                <span class="on-text">ظاهر بالموقع</span><span class="off-text">مخفي</span>
              </label>
            </div>
          @endif
        </div>
        <div class="card-body">
          @if(! empty($section['hint']))
            <div class="section-hint"><i class="fa-solid fa-lightbulb"></i><span>{{ $section['hint'] }}</span></div>
          @endif

          @if(! empty($section['seo']))
            @php $pairs = collect($section['fields'])->chunk(2); @endphp
            @foreach($pairs as $pair)
              @php [$tf, $df] = array_values($pair->all()); @endphp
              <div class="fields" style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px dashed var(--line-2)">
                @include('admin.partials.field', ['f' => $tf, 'value' => $values[$tf['key']] ?? null])
                @include('admin.partials.field', ['f' => $df, 'value' => $values[$df['key']] ?? null])
                <div class="field">
                  <span class="label"><i class="fa-brands fa-google"></i> هكذا ستظهر في نتائج جوجل</span>
                  <div class="serp" data-serp data-title-from="#f_{{ $tf['key'] }}" data-desc-from="#f_{{ $df['key'] }}">
                    <div class="serp-url">{{ preg_replace('#^https?://#', '', rtrim(setting('site_url'), '/')) }}</div>
                    <div class="serp-title"></div>
                    <div class="serp-desc"></div>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="fields">
              @foreach($section['fields'] as $f)
                @include('admin.partials.field', ['f' => $f, 'value' => $values[$f['key']] ?? null])
              @endforeach
            </div>
          @endif
        </div>
      </section>
    @endforeach
  </form>
</div>

@include('admin.partials.save-dock')
@endsection
