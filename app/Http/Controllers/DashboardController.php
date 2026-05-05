<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Room;
use App\Models\TechnicianAssignment;
use App\Models\User; // Ganti Facility jadi Room
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'teknisi') {
            return $this->teknisiDashboard();
        } else {
            return $this->pelaporDashboard();
        }
    }

    private function pelaporDashboard()
    {
        $userId = Auth::id();
        $stats = [
            'total_reports' => Report::where('reporter_id', $userId)->count(),
            'pending_reports' => Report::where('reporter_id', $userId)->where('status', 'Pending')->count(),
            'in_progress_reports' => Report::where('reporter_id', $userId)->where('status', 'Processing')->count(),
            'completed_reports' => Report::where('reporter_id', $userId)->where('status', 'Completed')->count(),
        ];

        // Panggil relasi room dan category
        $recent_reports = Report::with(['room', 'category'])
            ->where('reporter_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pelapor.dashboard', compact('stats', 'recent_reports'));
    }

    private function adminDashboard()
    {
        $stats = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'Pending')->count(),
            'in_progress_reports' => Report::where('status', 'Processing')->count(),
            'completed_reports' => Report::where('status', 'Completed')->count(),
            'total_users' => User::count(),
            'total_technicians' => User::where('role', 'teknisi')->count(),
            'total_students' => User::where('role', 'pelapor')->count(),
            'total_rooms' => Room::count(), // Dulu total_facilities

            // Cek SLA pakai kolom 'deadline'
            'sla_violations' => Report::where('status', '!=', 'Completed')
                ->where('sla_deadline', '<', now())
                ->count(),
        ];

        // Panggil relasi reporter, room, dan category
        $recent_reports = Report::with(['reporter', 'room', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $reports_by_status = Report::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Ganti perhitungan fasilitas jadi ruangan (Top 5 Ruangan Paling Sering Rusak)
        $reports_by_room = Room::withCount('reports')
            ->orderBy('reports_count', 'desc')
            ->limit(5)
            ->get();

        $recent_users = User::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_reports', 'reports_by_status', 'reports_by_room', 'recent_users'));
    }

    private function teknisiDashboard()
    {
        $technicianId = Auth::id();

        // 1. Sesuaikan Key dengan Blade lu (active_tasks & completed_tasks)
        $stats = [
            'active_tasks' => TechnicianAssignment::where('technician_id', $technicianId)
                ->whereNull('completed_at')
                ->count(),
            'completed_tasks' => TechnicianAssignment::where('technician_id', $technicianId)
                ->whereNotNull('completed_at')
                ->count(),
        ];

        // 2. Ambil data tugas aktif untuk tabel
        // Note: Pastikan relasi 'report' sudah ada di model TechnicianAssignment
        $active_tasks = TechnicianAssignment::with(['report.room'])
            ->where('technician_id', $technicianId)
            ->whereNull('completed_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teknisi.dashboard', compact('stats', 'active_tasks'));
    }
}
