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
            'komputer' => Facility::where('category', 'Komputer')->count(),
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
            'category' => 'required|in:Lab,Komputer',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:baik,perlu_perbaikan,rusak',
            'sla_hours' => 'required|integer|min:1|max:168',
            'is_active' => 'boolean',
        ]);

        Facility::create($request->all());

        return redirect()->route('admin.facilities')->with('success', 'Fasilitas berhasil ditambahkan');
    }

    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Lab,Komputer',
            'location' => 'required|string|max:255',
            'status' => 'required|in:baik,perlu_perbaikan,rusak',
            'sla_hours' => 'required|integer|min:1|max:168',
            'is_active' => 'boolean',
        ]);

        $facility->update($request->all());
        return redirect()->route('admin.facilities')->with('success', 'Fasilitas berhasil diperbarui');
    }

    public function destroy(Facility $facility)
    {
        if ($facility->reports()->count() > 0) {
            return back()->with('error', 'Fasilitas memiliki laporan, tidak dapat dihapus');
        }
        $facility->delete();
        return back()->with('success', 'Fasilitas berhasil dihapus');
    }
}