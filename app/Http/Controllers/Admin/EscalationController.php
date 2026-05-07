<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EscalationController extends Controller
{
   public function index()
{
    $hasEscalatedAt = Schema::hasColumn('reports', 'escalated_at');
    
    $query = Report::where('status', '!=', 'completed')
        ->where('sla_deadline', '<', now());
    
    // Jangan tampilkan laporan yang sudah dieskalasi
    if ($hasEscalatedAt) {
        $query->whereNull('escalated_at');
    }
    
    // Atau filter berdasarkan status
    $query->whereNotIn('status', ['escalated', 'processing']);
    
    $escalatedReports = $query->with(['reporter', 'room'])->get();
    
    return view('admin.escalation.index', compact('escalatedReports'));
}
    
   public function escalate(Report $report)
{
    $hasEscalatedAt = Schema::hasColumn('reports', 'escalated_at');
    
    $updateData = [];
    
    if ($hasEscalatedAt) {
        $updateData['escalated_at'] = now();
        $updateData['escalated_by'] = auth()->id();
    }
    
    // Opsi A: Set deadline ke masa depan (kasih waktu tambahan 2 hari)
    $updateData['sla_deadline'] = now()->addDays(2);
    
    // Opsi B: Set deadline menjadi null (tidak dianggap terlambat)
    // $updateData['sla_deadline'] = null;
    
    // Opsi C: Hanya ubah status
    $updateData['status'] = 'escalated';
    
    $report->update($updateData);
    
    return redirect()->route('admin.escalation.index')
        ->with('success', 'Report escalated successfully.');
}
    
    public function ignore(Report $report)
    {
        $hasEscalatedAt = Schema::hasColumn('reports', 'escalated_at');
        
        if ($hasEscalatedAt) {
            $report->update(['escalated_at' => now()]);
        }
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->route('admin.escalation.index')
            ->with('success', 'Report ignored successfully.');
    }
}