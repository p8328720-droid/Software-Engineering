<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\Facility;

class DashboardController extends Controller
{
    public function index()
    {
        $slaViolations = 0;
        try {
            $slaViolations = Report::where('status', '!=', 'completed')
                ->where('sla_deadline', '<', now())
                ->count();
        } catch (\Exception $e) {
            $slaViolations = 0;
        }

        $stats = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('status', 'in_progress')->count(),
            'completed_reports' => Report::where('status', 'completed')->count(),
            'rejected_reports' => Report::where('status', 'rejected')->count(),
            'total_users' => User::count(),
            'total_technicians' => User::where('role', 'teknisi')->count(),
            'total_students' => User::where('role', 'mahasiswa')->count(),
            'total_facilities' => Facility::count(),
            'sla_violations' => $slaViolations,
        ];

        $recent_reports = Report::with(['user', 'facility'])->latest()->limit(10)->get();
        $recent_users = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_reports', 'recent_users'));
    }
}