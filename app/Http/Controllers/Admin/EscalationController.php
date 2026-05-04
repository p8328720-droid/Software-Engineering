<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;

class EscalationController extends Controller
{
    public function index()
    {
        $escalatedReports = Report::where('status', '!=', 'completed')
            ->where('sla_deadline', '<', now())
            ->whereNull('escalated_at')
            ->with(['user', 'facility', 'technicianAssignments.technician'])
            ->get();
            
        return view('admin.escalation.index', compact('escalatedReports'));
    }
}