<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SLA;
use Illuminate\Http\Request;

class SLAController extends Controller
{
    public function index()
    {
        $slaRules = SLA::orderBy('facility_category')->orderBy('urgency')->get();
        
        $categories = SLA::select('facility_category')->distinct()->pluck('facility_category');
        
        return view('admin.sla.index', compact('slaRules', 'categories'));
    }

    public function edit($id)
    {
        $sla = SLA::findOrFail($id);
        return view('admin.sla.edit', compact('sla'));
    }

    public function update(Request $request, $id)
    {
        $sla = SLA::findOrFail($id);
        
        $request->validate([
            'response_hours' => 'required|integer|min:1|max:72',
            'resolution_hours' => 'required|integer|min:1|max:168',
            'is_active' => 'boolean',
        ]);

        $sla->update([
            'response_hours' => $request->response_hours,
            'resolution_hours' => $request->resolution_hours,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.sla')
            ->with('success', 'Aturan SLA berhasil diperbarui');
    }
}