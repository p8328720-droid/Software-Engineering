<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $activeTasks = Report::with('facility')
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest()
            ->get();
        
        return view('teknisi.tasks.index', compact('activeTasks'));
    }

    public function show(Report $report)
    {
        if ($report->status == 'pending') {
            $report->update(['status' => 'in_progress']);
            
            ReportStatus::create([
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'status' => 'in_progress',
                'description' => 'Teknisi mulai memproses laporan'
            ]);
        }
        
        $report->load('comments.user', 'statusHistory.user', 'facility', 'user');
        return view('teknisi.tasks.show', compact('report'));
    }
}