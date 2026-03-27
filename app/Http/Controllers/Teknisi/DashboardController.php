<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\TechnicianAssignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'active_tasks' => TechnicianAssignment::where('technician_id', Auth::id())
                ->whereNull('completed_at')
                ->count(),
            'completed_tasks' => TechnicianAssignment::where('technician_id', Auth::id())
                ->whereNotNull('completed_at')
                ->count(),
        ];
        
        $active_tasks = TechnicianAssignment::with('report')
            ->where('technician_id', Auth::id())
            ->whereNull('completed_at')
            ->get();
            
        return view('teknisi.dashboard', compact('stats', 'active_tasks'));
    }
}