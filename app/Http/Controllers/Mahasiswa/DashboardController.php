<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_reports' => Report::where('user_id', Auth::id())->count(),
            'pending_reports' => Report::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'completed_reports' => Report::where('user_id', Auth::id())->where('status', 'completed')->count(),
            'in_progress_reports' => Report::where('user_id', Auth::id())->where('status', 'in_progress')->count(),
        ];
        
        $recent_reports = Report::with('facility')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('mahasiswa.dashboard', compact('stats', 'recent_reports'));
    }
}