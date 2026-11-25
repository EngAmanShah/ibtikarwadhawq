<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Support\Fields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/** متحكم عام لإدارة القوائم (إضافة / تعديل / حذف / ترتيب / إظهار) */
abstract class CrudController extends Controller
{
    protected string $model;
    protected string $base;          // admin.slides
    protected string $type;          // اسم النوع في مسار الترتيب
    protected string $title;
    protected string $singular;
    protected string $icon;
    protected string $description = '';
    protected string $folder = 'media';
    protected string $layout = 'grid';   // grid | rows
    protected ?string $imageField = null;
    protected string $titleField = 'title';
    protected ?string $subField = null;
    protected ?string $preview = '/';
    protected bool $contain = false;

    abstract protected function fields(?Model $item = null): array;

    protected function params(): array { return []; }
    protected function query() { return ($this->model)::query(); }
    protected function extra(Request $request, ?Model $item = null): array { return []; }

    protected function meta(): array
    {
        return [
            'title' => $this->title, 'singular' => $this->singular, 'icon' => $this->icon, 'description' => $this->description,
            'layout' => $this->layout, 'image' => $this->imageField, 'titleField' => $this->titleField, 'sub' => $this->subField,
            'preview' => $this->preview, 'contain' => $this->contain, 'type' => $this->type,
            'index' => route("{$this->base}.index", $this->params()),
            'create' => route("{$this->base}.create", $this->params()),
            'store' => route("{$this->base}.store", $this->params()),
            'edit' => fn ($m) => route("{$this->base}.edit", [...array_values($this->params()), $m->id]),
            'update' => fn ($m) => route("{$this->base}.update", [...array_values($this->params()), $m->id]),
            'destroy' => fn ($m) => route("{$this->base}.destroy", [...array_values($this->params()), $m->id]),
        ];
    }

    public function index()
    {
        return view('admin.crud.index', ['meta' => $this->meta(), 'items' => $this->query()->ordered()->get()]);
    }

    public function create()
    {
        return view('admin.crud.form', ['meta' => $this->meta(), 'item' => null, 'fields' => $this->fields()]);
    }

    public function edit(...$args)
    {
        $item = $this->find(end($args));
        return view('admin.crud.form', ['meta' => $this->meta(), 'item' => $item, 'fields' => $this->fields($item)]);
    }

    public function store(Request $request)
    {
        $item = new ($this->model);
        $this->save($request, $item);
        Activity::log('إضافة', $this->singular . ': ' . $item->{$this->titleField}, 'fa-plus');
        return redirect($this->meta()['index'])->with('ok', 'تمت الإضافة ونشرها على الموقع');
    }

    public function update(Request $request, ...$args)
    {
        $item = $this->find(end($args));
        $this->save($request, $item);
        Activity::log('تحديث', $this->singular . ': ' . $item->{$this->titleField}, 'fa-pen');
        return back()->with('ok', 'تم حفظ التعديلات');
    }

    public function destroy(...$args)
    {
        $item = $this->find(end($args));
        foreach ($this->fields($item) as $f) {
            if (in_array($f['type'] ?? '', ['image', 'file'])) \App\Support\Uploader::delete($item->{$f['key']});
        }
        Activity::log('حذف', $this->singular . ': ' . $item->{$this->titleField}, 'fa-trash-can');
        $item->delete();
        return redirect($this->meta()['index'])->with('ok', 'تم الحذف');
    }

    protected function find($id): Model
    {
        return $this->query()->findOrFail($id);
    }

    protected function save(Request $request, Model $item): void
    {
        $fields = $this->fields($item);
        $rules = Fields::rules($fields);
        foreach ($fields as $f) {
            if (! empty($f['required']) && in_array($f['type'] ?? '', ['image', 'file']) && ! $item->{$f['key']}) {
                $rules[$f['key']][0] = 'required';
            }
        }
        $request->validate($rules + ($this->extraRules($item)), [], Fields::attributes($fields));

        foreach ($fields as $f) {
            $val = Fields::value($request, $f, $item->{$f['key']}, $this->folder);
            if (($f['type'] ?? '') === 'toggle') $val = $val === '1';
            $item->{$f['key']} = $val;
        }
        foreach ($this->extra($request, $item) as $k => $v) $item->{$k} = $v;
        $item->save();
    }

    protected function extraRules(Model $item): array { return []; }
}
