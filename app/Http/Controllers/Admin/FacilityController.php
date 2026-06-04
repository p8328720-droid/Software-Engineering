<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::orderBy('category')->orderBy('name')->paginate(10);
        
        $stats = [
            'total' => Facility::count(),
            'lab' => Facility::where('category', 'Lab')->count(),
            'kelas' => Facility::where('category', 'Kelas')->count(),
            'perlu_perbaikan' => Facility::where('status', 'perlu_perbaikan')->count(),
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
            'category' => 'required|in:Lab,Kelas',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:baik,perlu_perbaikan,rusak',
            'sla_hours' => 'required|integer|min:1|max:168',
            'is_active' => 'boolean',
        ]);

       Facility::create($request->only([
    'name', 'category', 'location', 'description',
    'status', 'sla_hours', 'is_active'
]));

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
            'category' => 'required|in:Lab,Kelas',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:baik,perlu_perbaikan,rusak',
            'sla_hours' => 'required|integer|min:1|max:168',
            'is_active' => 'boolean',
        ]);

        $facility->update($request->only([
    'name', 'category', 'location', 'description',
    'status', 'sla_hours', 'is_active'
]));
        
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