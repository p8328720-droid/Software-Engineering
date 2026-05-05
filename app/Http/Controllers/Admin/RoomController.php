<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room; // Pastikan model sudah Room
use App\Models\Report;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        // Eager load count laporan yang masih pending/in_progress
        $rooms = Room::withCount(['reports' => function($q) {
            $q->whereIn('status', ['pending', 'in_progress']);
        }])->orderBy('name')->paginate(10);
        
        $stats = [
            'total' => Room::count(),
            // Hitung ruangan yang punya minimal 1 laporan aktif
            'room_with_reports' => Room::whereHas('reports', function($q) {
                $q->whereIn('status', ['pending', 'in_progress']);
            })->count(),
        ];
        
        return view('admin.rooms.index', compact('rooms', 'stats'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Room::create($request->only(['name', 'location', 'description']));

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan ke sistem');
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name,'.$id,
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $room->update($request->only(['name', 'location', 'description']));

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Informasi ruangan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        
        // Cek apakah ada laporan (transaksi) di ruangan ini
        if ($room->reports()->count() > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Gagal menghapus! Ruangan ini memiliki riwayat laporan kerusakan.');
        }
        
        $room->delete();
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus dari sistem');
    }
}