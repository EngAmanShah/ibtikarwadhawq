<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models;
use Illuminate\Http\Request;

class ReorderController extends Controller
{
    protected const MAP = [
        'slides' => Models\Slide::class, 'clients' => Models\Client::class, 'documents' => Models\Document::class,
        'branches' => Models\Branch::class, 'blocks' => Models\Block::class, 'services' => Models\Service::class,
        'items' => Models\ServiceItem::class,
    ];

    protected function model(string $type): string
    {
        abort_unless(isset(self::MAP[$type]), 404);
        return self::MAP[$type];
    }

    public function reorder(Request $request, string $type)
    {
        $class = $this->model($type);
        $ids = array_map('intval', (array) $request->input('ids', []));
        foreach ($ids as $i => $id) $class::whereKey($id)->update(['sort' => $i + 1]);
        return response()->json(['ok' => true]);
    }

    public function toggle(string $type, int $id)
    {
        $item = ($this->model($type))::findOrFail($id);
        $item->is_active = ! $item->is_active;
        $item->save();
        return response()->json(['ok' => true, 'active' => (bool) $item->is_active]);
    }
}
