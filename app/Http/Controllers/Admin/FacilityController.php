<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::orderBy('name')->paginate(10);
        
        $stats = [
            'total' => Facility::count(),
            'baik' => Facility::where('status', 'baik')->count(),
            'perlu_perbaikan' => Facility::where('status', 'perlu_perbaikan')->count(),
            'rusak' => Facility::where('status', 'rusak')->count(),
        ];
        
        return view('admin.facilities.index', compact('facilities', 'stats'));
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:baik,perlu_perbaikan,rusak',
            'sla_hours' => 'required|integer|min:1|max:168',
            'is_active' => 'boolean',
        ]);

        Facility::create([
            'name' => $request->name,
            'category' => $request->category,
            'location' => $request->location,
            'description' => $request->description,
            'status' => $request->status,
            'sla_hours' => $request->sla_hours,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.facilities')
            ->with('success', 'Fasilitas berhasil ditambahkan');
    }

    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:baik,perlu_perbaikan,rusak',
            'sla_hours' => 'required|integer|min:1|max:168',
            'is_active' => 'boolean',
        ]);

        $facility->update([
            'name' => $request->name,
            'category' => $request->category,
            'location' => $request->location,
            'description' => $request->description,
            'status' => $request->status,
            'sla_hours' => $request->sla_hours,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.facilities')
            ->with('success', 'Fasilitas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        
        if ($facility->reports()->count() > 0) {
            return redirect()->route('admin.facilities')
                ->with('error', 'Fasilitas memiliki laporan, tidak dapat dihapus');
        }
        
        $facility->delete();
        
        return redirect()->route('admin.facilities')
            ->with('success', 'Fasilitas berhasil dihapus');
    }
}