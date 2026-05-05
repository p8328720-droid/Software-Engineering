<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Report;
use App\Models\TechnicianAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        // Ganti 'report.category' menjadi 'report.sla' sesuai struktur baru
        $activeTasks = TechnicianAssignment::with(['report.room', 'report.sla'])
            ->where('technician_id', Auth::id())
            ->whereNull('completed_at')
            ->orderBy('assigned_at', 'desc')
            ->get();

        $completedTasks = TechnicianAssignment::with(['report.room', 'report.sla'])
            ->where('technician_id', Auth::id())
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('teknisi.tasks.index', compact('activeTasks', 'completedTasks'));
    }

    public function show($id)
    {
        // Pastikan eager loading mengarah ke 'sla'
        $task = TechnicianAssignment::with(['report.reporter', 'report.room', 'report.sla'])
            ->where('technician_id', Auth::id())
            ->findOrFail($id);

        return view('teknisi.tasks.show', compact('task'));
    }

    public function complete(Request $request, $id)
    {
        $request->validate([
            'technician_note' => 'required|string|max:1000',
            'completion_image' => 'nullable|image|max:2048',
        ]);

        // 1. Ambil data penugasan
        $assignment = TechnicianAssignment::where('technician_id', Auth::id())
            ->findOrFail($id);

        // 2. Ambil data laporan terkait
        $report = Report::findOrFail($assignment->report_id);

        // 3. Update waktu selesai di tabel penugasan
        $assignment->update([
            'completed_at' => now(),
        ]);

        // 4. Update status laporan utama menjadi 'completed'
        // Kita juga isi 'resolved_at' sesuai migrasi yang lu buat tadi
        $report->update([
            'status' => 'completed',
            'resolved_at' => now(),
        ]);

        // 5. Handling upload bukti foto (jika ada)
        if ($request->hasFile('completion_image')) {
            $imagePath = $request->file('completion_image')->store('completions', 'public');
            // Jika lu punya kolom image_path di TechnicianAssignment, simpan di sini:
            // $assignment->update(['image_path' => $imagePath]);
        }

        // 6. Catat ke Audit Log
        AuditLog::create([
            'report_id' => $report->id,
            'user_id' => Auth::id(),
            'status_changed_to' => 'completed',
            'notes' => '[PENYELESAIAN] '.$request->technician_note,
        ]);

        return redirect()->route('teknisi.tasks.index')
            ->with('success', 'Selamat! Tugas telah diselesaikan dan laporan telah ditutup.');
    }
}
