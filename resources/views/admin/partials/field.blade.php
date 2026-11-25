@php
    /** @var array $f  تعريف الحقل */
    $name = $f['name'] ?? $f['key'];
    $type = $f['type'] ?? 'text';
    $id = 'f_' . preg_replace('/\W+/', '_', $name);
    $value = old($name, $value ?? null);
    $width = ! empty($f['half']) ? 'half' : (! empty($f['third']) ? 'third' : '');
    $err = $errors->first($name);
    $icons = ['url' => 'fa-link', 'email' => 'fa-at', 'phone' => 'fa-phone'];
    $inputIcon = $f['icon'] ?? ($icons[$type] ?? null);
@endphp
<div class="field {{ $width }}">
  @if($type !== 'toggle')
    <label for="{{ $id }}">{{ $f['label'] }} @if(! empty($f['required']))<span style="color:var(--red)">*</span>@endif</label>
  @endif

  @switch($type)
    @case('textarea')
    @case('code')
      <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $f['rows'] ?? ($type === 'code' ? 5 : 3) }}" class="textarea {{ $type === 'code' ? 'code' : '' }} {{ $err ? 'is-invalid' : '' }}" @if(! empty($f['counter'])) data-counter="{{ $f['counter'] }}" @endif @if(! empty($f['placeholder'])) placeholder="{{ $f['placeholder'] }}" @endif @if(! empty($f['required'])) required @endif>{{ $value }}</textarea>
      @break

    @case('image')
    @case('file')
      @php $isImg = $type === 'image'; $shape = $f['shape'] ?? ''; @endphp
      <div class="media-field">
        <div class="dropzone {{ $isImg ? $shape : 'doc' }} {{ $err ? 'is-invalid' : '' }}">
          @if($value && $isImg)
            <img src="{{ media($value) }}" alt="">
          @elseif($value)
            <div class="doc-chip"><i class="fa-solid fa-file-pdf"></i><div><strong>{{ basename($value) }}</strong><small>الملف الحالي — اسحب ملفًا جديدًا هنا لاستبداله</small></div></div>
          @else
            <div class="dz-empty"><i class="fa-solid {{ $isImg ? 'fa-cloud-arrow-up' : 'fa-file-arrow-up' }}"></i>اسحب {{ $isImg ? 'الصورة' : 'الملف' }} هنا أو اضغط للاختيار</div>
          @endif
          <span class="dz-new"><i class="fa-solid fa-check"></i> جديد</span>
          <div class="dz-overlay"><span><i class="fa-solid fa-arrows-rotate"></i> اضغط أو اسحب للاستبدال</span></div>
          <input id="{{ $id }}" type="file" name="{{ $name }}" accept="{{ $isImg ? 'image/jpeg,image/png,image/webp,image/gif,image/avif' : 'application/pdf' }}" data-max="{{ $isImg ? 10 : 30 }}" @if(! empty($f['required']) && ! $value) required @endif>
        </div>
        <div class="media-meta">
          @if($value)<a href="{{ media($value) }}" target="_blank"><i class="fa-solid fa-up-right-from-square"></i> فتح</a>@endif
          <span>{{ $isImg ? 'JPG / PNG / WEBP — تُضغط تلقائيًا' : 'PDF حتى 30 ميجا' }}</span>
          @if($value && empty($f['keep']) && empty($f['required']))
            <label><input type="checkbox" name="{{ $name }}__remove" value="1"> إزالة</label>
          @endif
        </div>
      </div>
      @break

    @case('color')
      <div class="color-field">
        <input type="color" value="{{ $value ?: '#bfa27e' }}" aria-label="{{ $f['label'] }}">
        <input type="text" id="{{ $id }}" name="{{ $name }}" class="input" value="{{ $value }}" maxlength="7" pattern="#[0-9a-fA-F]{6}">
      </div>
      <div class="color-presets">
        @foreach(['#bfa27e', '#a88c66', '#8c7355', '#b08d57', '#9a7b4f', '#6d5d4b', '#2f2a25', '#3c6fa8'] as $c)
          <button type="button" data-color="{{ $c }}" style="background:{{ $c }}" title="{{ $c }}"></button>
        @endforeach
      </div>
      @break

    @case('toggle')
      <label class="switch">
        <input type="hidden" name="{{ $name }}" value="0">
        <input type="checkbox" id="{{ $id }}" name="{{ $name }}" value="1" @checked($value === null ? ($f['default'] ?? true) : (bool) $value)>
        <span class="track"></span>
        <span>{{ $f['label'] }}</span>
      </label>
      @break

    @case('list')
      @php $items = is_array($value) ? $value : (json_decode((string) $value, true) ?: []); @endphp
      <div class="list-field" data-name="{{ $name }}">
        <input type="hidden" name="{{ $name }}__list" value="1">
        <div class="list-rows">
          @foreach($items as $item)
            <div class="list-row"><span class="handle"><i class="fa-solid fa-grip-vertical"></i></span><input class="input" name="{{ $name }}[]" value="{{ $item }}"><button type="button" class="btn btn-danger btn-icon" aria-label="حذف"><i class="fa-solid fa-trash-can"></i></button></div>
          @endforeach
        </div>
        <button type="button" class="btn btn-soft btn-sm list-add"><i class="fa-solid fa-plus"></i> {{ $f['add_label'] ?? 'إضافة سطر' }}</button>
      </div>
      @break

    @case('icon')
      @php $value = $value ?: 'fa-star'; @endphp
      <div class="icon-picker">
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        <button type="button" class="icon-picker-btn" id="{{ $id }}"><span class="ip-preview"><i class="fa-solid {{ $value }}"></i></span><span>اختر أيقونة</span><code>{{ $value }}</code><i class="fa-solid fa-chevron-down"></i></button>
        <div class="icon-pop">
          <input class="input icon-search" placeholder="ابحث بالإنجليزية… مثل: house, paint" dir="ltr">
          <div class="icon-grid">
            @foreach(config('cms.icons') as $ic)
              <button type="button" data-icon="{{ $ic }}" class="{{ $ic === $value ? 'sel' : '' }}" title="{{ $ic }}"><i class="fa-solid {{ $ic }}"></i></button>
            @endforeach
          </div>
          <div class="help"><i class="fa-solid fa-lightbulb"></i><span>تريد أيقونة أخرى؟ اختر اسمها من <a href="https://fontawesome.com/search?o=r&m=free&s=solid" target="_blank" rel="noopener">مكتبة Font Awesome</a> واكتبه في البحث ثم اضغط Enter.</span></div>
        </div>
      </div>
      @break

    @case('select')
      <select id="{{ $id }}" name="{{ $name }}" class="select">
        @foreach($f['options'] as $k => $label)<option value="{{ $k }}" @selected((string) $value === (string) $k)>{{ $label }}</option>@endforeach
      </select>
      @break

    @default
      @php $htmlType = ['url' => 'url', 'email' => 'email', 'phone' => 'tel', 'date' => 'date', 'number' => 'number'][$type] ?? 'text'; $ltr = ! empty($f['ltr']) || in_array($type, ['url', 'email', 'phone']); @endphp
      @if($inputIcon)<div class="input-wrap"><i class="{{ str_contains($inputIcon, 'fa-brands') ? $inputIcon : 'fa-solid ' . $inputIcon }}"></i>@endif
      <input id="{{ $id }}" type="{{ $htmlType }}" name="{{ $name }}" value="{{ $value }}" class="input {{ $ltr ? 'ltr' : '' }} {{ $err ? 'is-invalid' : '' }}" @if(! empty($f['counter'])) data-counter="{{ $f['counter'] }}" @endif @if(! empty($f['placeholder'])) placeholder="{{ $f['placeholder'] }}" @endif @if(! empty($f['required'])) required @endif>
      @if($inputIcon)</div>@endif
  @endswitch

  @if(! empty($f['counter']))
    <div class="counter"><div class="counter-bar"><span></span></div><span class="counter-text"></span></div>
  @endif
  @if($err)<div class="error-text">{{ $err }}</div>@endif
  @if(! empty($f['help']))<div class="help"><i class="fa-solid fa-circle-info"></i><span>{{ $f['help'] }}</span></div>@endif
</div>
