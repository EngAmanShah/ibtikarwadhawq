<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $q = trim((string) $request->query('q'));
        $leads = Lead::query()
            ->when($status && isset(Lead::STATUSES[$status]), fn ($x) => $x->where('status', $status))
            ->when($q, fn ($x) => $x->where(fn ($w) => $w->where('name', 'like', "%$q%")->orWhere('phone', 'like', "%$q%")->orWhere('message', 'like', "%$q%")))
            ->latest()->paginate(20)->withQueryString();

        $counts = Lead::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');
        return view('admin.leads.index', compact('leads', 'counts', 'status', 'q'));
    }

    public function show(Lead $lead)
    {
        return view('admin.leads.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate(['status' => 'required|in:' . implode(',', array_keys(Lead::STATUSES)), 'notes' => 'nullable|string|max:5000']);
        $lead->update($data);
        return back()->with('ok', 'تم تحديث الطلب');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.leads.index')->with('ok', 'تم حذف الطلب');
    }

    public function export()
    {
        $rows = Lead::latest()->get();
        $out = "\xEF\xBB\xBF" . "الاسم,الجوال,الخدمة,نوع المساحة,التفاصيل,الحالة,ملاحظات,التاريخ\n";
        foreach ($rows as $l) {
            $cells = [$l->name, $l->phone, $l->service, $l->space, $l->message, $l->statusInfo()['label'], $l->notes, $l->created_at->format('Y-m-d H:i')];
            $out .= implode(',', array_map(fn ($c) => '"' . str_replace('"', '""', preg_replace('/^[=+\-@]/', "'$0", (string) $c)) . '"', $cells)) . "\n";
        }
        return response($out, 200, ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => 'attachment; filename="leads-' . now()->format('Y-m-d') . '.csv"']);
    }
}
