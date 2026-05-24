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
        
        if ($request->filled('table') && $request->table != '') {
            $query->where(function($q) use ($request) {
                $q->where('table_name', $request->table)
                  ->orWhere('auditable_type', 'like', '%' . $request->table . '%');
            });
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.audit.index', compact('logs'));
    }
    
    /**
     * Get detail of a specific audit log (AJAX)
     */
    public function detail($id)
    {
        try {
            $log = AuditLog::with('user')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'log' => [
                    'id' => $log->id,
                    'created_at' => $log->created_at->format('d/m/Y H:i:s'),
                    'user_name' => $log->user ? $log->user->name : 'System',
                    'user_role' => $log->user ? $log->user->role : null,
                    'action' => $log->action,
                    'table_name' => $log->table_name,
                    'auditable_type' => $log->auditable_type,
                    'record_id' => $log->record_id,
                    'auditable_id' => $log->auditable_id,
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                    'ip_address' => $log->ip_address,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}