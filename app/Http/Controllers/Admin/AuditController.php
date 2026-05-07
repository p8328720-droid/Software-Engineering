<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query();
        
        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }
        
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Check if columns exist before trying to use them
        $hasTableName = Schema::hasColumn('audit_logs', 'table_name');
        $hasAction = Schema::hasColumn('audit_logs', 'action');
        
        $tables = $hasTableName ? AuditLog::select('table_name')->distinct()->pluck('table_name') : collect([]);
        $actions = $hasAction ? AuditLog::select('action')->distinct()->pluck('action') : collect([]);
        
        return view('admin.audit.index', compact('logs', 'tables', 'actions'));
    }
}