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
            'total'          => Facility::count(),
            'lab'            => Facility::where('category', 'Lab')->count(),
            'kelas'          => Facility::where('category', 'Kelas')->count(),
            'perlu_perbaikan'=> Facility::where('status', 'perlu_perbaikan')->count(),
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
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Lab,Kelas',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:baik,perlu_perbaikan,rusak',
            'sla_hours'   => 'required|integer|min:1|max:168',
            'is_active'   => 'boolean',
        ]);

        $data = $request->only([
            'name', 'category', 'location', 'description',
            'status', 'sla_hours',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        Facility::create($data);

        return redirect()->route('admin.facilities')
            ->with('success', 'Fasilitas berhasil ditambahkan');
    }

    // ✅ Route Model Binding — Laravel otomatis resolve & 404 jika tidak ditemukan
    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    // ✅ Route Model Binding — parameter '$facility' harus cocok dengan {facility} di route
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Lab,Kelas',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:baik,perlu_perbaikan,rusak',
            'sla_hours'   => 'required|integer|min:1|max:168',
            'is_active'   => 'boolean',
        ]);

        $data = $request->only([
            'name', 'category', 'location', 'description',
            'status', 'sla_hours',
        ]);
        // ✅ Fix: checkbox tidak terkirim saat unchecked, pakai boolean() helper
        $data['is_active'] = $request->boolean('is_active');

        $facility->update($data);

        return redirect()->route('admin.facilities')
            ->with('success', 'Fasilitas berhasil diperbarui');
    }

    // ✅ Route Model Binding — tidak perlu findOrFail() manual
    public function destroy(Facility $facility)
    {
        if ($facility->reports()->count() > 0) {
            return redirect()->route('admin.facilities')
                ->with('error', 'Fasilitas memiliki laporan, tidak dapat dihapus');
        }

        $facility->delete();

        return redirect()->route('admin.facilities')
            ->with('success', 'Fasilitas berhasil dihapus');
    }
}