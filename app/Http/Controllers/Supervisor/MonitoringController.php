<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TechnicianAssignment;

class MonitoringController extends Controller
{
    public function index()
    {
        $technicians = User::where('role', 'teknisi')->get();
        
        $performance = [];
        foreach ($technicians as $tech) {
            $tasks = TechnicianAssignment::where('technician_id', $tech->id)->get();
            $completed = $tasks->whereNotNull('completed_at');
            
            $performance[] = [
                'technician' => $tech,
                'total_tasks' => $tasks->count(),
                'completed_tasks' => $completed->count(),
                'completion_rate' => $tasks->count() > 0 ? ($completed->count() / $tasks->count()) * 100 : 0,
            ];
        }
        
        return view('supervisor.monitoring.index', compact('performance'));
    }

    public function show($id)
    {
        $technician = User::where('role', 'teknisi')->findOrFail($id);
        
        $tasks = TechnicianAssignment::with('report')
            ->where('technician_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('supervisor.monitoring.show', compact('technician', 'tasks'));
    }
}