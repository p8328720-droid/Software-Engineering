<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RoomController extends Controller
{
    public function index()
    {
        $hasRoomId = Schema::hasColumn('reports', 'room_id');
        
        if (!$hasRoomId) {
            $rooms = Room::orderBy('name')->paginate(10);
            $stats = [
                'total' => Room::count(),
                'room_with_reports' => 0,
            ];
            
            return view('admin.rooms.index', compact('rooms', 'stats'))
                ->with('warning', 'Report counts are not available yet. Please run migrations to add room_id column.');
        }
        
        try {
            $rooms = Room::withCount(['reports' => function($q) {
                $q->whereIn('status', ['pending', 'in_progress']);
            }])->orderBy('name')->paginate(10);
            
            $stats = [
                'total' => Room::count(),
                'room_with_reports' => Room::whereHas('reports', function($q) {
                    $q->whereIn('status', ['pending', 'in_progress']);
                })->count(),
            ];
            
            return view('admin.rooms.index', compact('rooms', 'stats'));
        } catch (\Exception $e) {
            $rooms = Room::orderBy('name')->paginate(10);
            $stats = [
                'total' => Room::count(),
                'room_with_reports' => 0,
            ];
            
            return view('admin.rooms.index', compact('rooms', 'stats'))
                ->with('error', 'Unable to load report counts: ' . $e->getMessage());
        }
    }
    
    public function create()
    {
        return view('admin.rooms.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:rooms',
            'building' => 'nullable|string',
            'floor' => 'nullable|integer',
            'capacity' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);
        
        Room::create($validated);
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room created successfully.');
    }
    
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }
    
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:rooms,code,' . $room->id,
            'building' => 'nullable|string',
            'floor' => 'nullable|integer',
            'capacity' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);
        
        $room->update($validated);
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully.');
    }
    
    public function destroy(Room $room)
    {
        $room->delete();
        
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}