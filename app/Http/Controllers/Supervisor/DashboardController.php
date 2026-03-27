<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_reports' => Report::count(),
            'sla_violations' => Report::where('status', '!=', 'completed')
                ->where('sla_deadline', '<', now())
                ->count(),
            'active_technicians' => User::where('role', 'teknisi')->count(),
        ];
        
        $reports = Report::with(['user', 'facility', 'technicianAssignments.technician'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('supervisor.dashboard', compact('stats', 'reports'));
    }
}