<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');
        
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        
        if ($request->filled('table')) {
            $query->where('table_name', $request->table);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $tables = AuditLog::select('table_name')->distinct()->pluck('table_name');
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        
        return view('admin.audit.index', compact('logs', 'tables', 'actions'));
    }
}