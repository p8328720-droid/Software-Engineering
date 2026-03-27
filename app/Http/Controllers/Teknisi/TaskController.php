<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\TechnicianAssignment;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $activeTasks = TechnicianAssignment::with('report')
            ->where('technician_id', Auth::id())
            ->whereNull('completed_at')
            ->get();
            
        $completedTasks = TechnicianAssignment::with('report')
            ->where('technician_id', Auth::id())
            ->whereNotNull('completed_at')
            ->get();
            
        return view('teknisi.tasks.index', compact('activeTasks', 'completedTasks'));
    }

    public function show($id)
    {
        $task = TechnicianAssignment::with(['report', 'report.user', 'report.facility'])
            ->where('technician_id', Auth::id())
            ->findOrFail($id);
            
        return view('teknisi.tasks.show', compact('task'));
    }
}