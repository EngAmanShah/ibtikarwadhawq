@php $tf = $meta['titleField']; @endphp
@if($meta['layout'] === 'grid')
<div class="items-grid rise" id="list" data-sortable-url="{{ route('admin.reorder', $meta['type']) }}">
  @foreach($items as $i => $item)
  <article class="item-card {{ $item->is_active ? '' : 'inactive' }}" data-id="{{ $item->id }}" data-search="{{ $item->$tf }}">
    <div class="item-media {{ $meta['contain'] ? 'contain' : '' }}">
      <span class="item-handle" title="اسحب للترتيب"><i class="fa-solid fa-grip-vertical"></i></span>
      <span class="item-order">{{ $i + 1 }}</span>
      @if($meta['image'] && $item->{$meta['image']})
        <img src="{{ media($item->{$meta['image']}) }}" alt="" loading="lazy">
      @else
        <i class="fa-solid {{ $item->icon ?? $meta['icon'] }} big-ico"></i>
      @endif
    </div>
    <div class="item-body">
      <h3>{{ $item->$tf }}</h3>
      @if($meta['sub'] && $item->{$meta['sub']})<p>{{ $item->{$meta['sub']} }}</p>@endif
      <div class="item-tags">
        @if($item instanceof \App\Models\Service)<span class="tag gold"><i class="fa-solid fa-list"></i> {{ $item->items_count ?? $item->items()->count() }} أقسام</span>@endif
        @if($item instanceof \App\Models\ServiceItem)<span class="tag"><i class="fa-solid fa-check"></i> {{ count($item->points ?? []) }} نقاط</span>@endif
        @if($item instanceof \App\Models\Document)
          @if($item->expiry_state === 'expired')<span class="tag red"><i class="fa-solid fa-triangle-exclamation"></i> منتهية</span>
          @elseif($item->expiry_state === 'soon')<span class="tag amber"><i class="fa-solid fa-clock"></i> تنتهي قريبًا</span>
          @elseif($item->expiry_state === 'ok')<span class="tag green">سارية حتى {{ $item->expires_at->format('Y/m/d') }}</span>@endif
          @if(! $item->file)<span class="tag red">بدون ملف</span>@endif
        @endif
      </div>
    </div>
    <div class="item-foot">
      <label class="switch" title="إظهار/إخفاء"><input type="checkbox" data-toggle-url="{{ route('admin.toggle', [$meta['type'], $item->id]) }}" @checked($item->is_active)><span class="track"></span></label>
      <div class="actions">
        <a class="btn btn-soft btn-sm" href="{{ $meta['edit']($item) }}"><i class="fa-solid fa-pen"></i> تعديل</a>
        <form method="post" action="{{ $meta['destroy']($item) }}" data-confirm="حذف «{{ $item->$tf }}»؟">@csrf @method('delete')<button class="btn btn-danger btn-icon" aria-label="حذف"><i class="fa-solid fa-trash-can"></i></button></form>
      </div>
    </div>
  </article>
  @endforeach
</div>
@else
<div class="rows rise" id="list" data-sortable-url="{{ route('admin.reorder', $meta['type']) }}">
  @foreach($items as $item)
  <div class="row-item {{ $item->is_active ? '' : 'inactive' }}" data-id="{{ $item->id }}" data-search="{{ $item->$tf }} {{ $item->label ?? '' }}">
    <span class="row-handle" title="اسحب للترتيب"><i class="fa-solid fa-grip-vertical"></i></span>
    <span class="row-ico"><i class="{{ fa($item->icon ?? $meta['icon']) }}"></i></span>
    <div class="row-main">
      <strong>{{ $item->$tf ?: ($item->label ?: '—') }}@if($item instanceof \App\Models\Block && $item->label && $item->$tf) <span class="tag">{{ $item->label }}</span>@endif</strong>
      @if($item instanceof \App\Models\Branch)
        <small dir="ltr" style="text-align:right">{{ $item->phone_display }} @if($item->map_src) <i class="fa-solid fa-map-location-dot" style="color:var(--gold);margin-inline:6px" title="عليه خريطة"></i>@endif</small>
      @endif
      @if($meta['sub'] && $item->{$meta['sub']} && $meta['sub'] !== 'label')<small>{{ $item->{$meta['sub']} }}</small>@endif
    </div>
    <label class="switch" title="إظهار/إخفاء"><input type="checkbox" data-toggle-url="{{ route('admin.toggle', [$meta['type'], $item->id]) }}" @checked($item->is_active)><span class="track"></span></label>
    <div class="row-actions">
      <a class="btn btn-soft btn-sm" href="{{ $meta['edit']($item) }}"><i class="fa-solid fa-pen"></i> تعديل</a>
      <form method="post" action="{{ $meta['destroy']($item) }}" data-confirm="حذف «{{ $item->$tf ?: $item->label }}»؟">@csrf @method('delete')<button class="btn btn-danger btn-icon" aria-label="حذف"><i class="fa-solid fa-trash-can"></i></button></form>
    </div>
  </div>
  @endforeach
</div>
@endif
