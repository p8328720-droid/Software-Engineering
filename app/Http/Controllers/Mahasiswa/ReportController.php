<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with('facility')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('mahasiswa.reports.index', compact('reports'));
    }

    public function create()
    {
        $facilities = Facility::where('is_active', true)->get();
        return view('mahasiswa.reports.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'facility_id' => 'required|exists:facilities,id',
            'location_detail' => 'required|string|max:255',
            'urgency' => 'required|in:low,medium,high',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';
        
        // Calculate SLA deadline
        $facility = Facility::find($request->facility_id);
        $slaHours = $facility->sla_hours;
        
        if ($request->urgency == 'high') {
            $slaHours = $slaHours * 0.5;
        } elseif ($request->urgency == 'medium') {
            $slaHours = $slaHours * 0.75;
        }
        
        $data['sla_deadline'] = now()->addHours($slaHours);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reports', 'public');
            $data['image_path'] = $path;
        }
        
        $report = Report::create($data);
        
        return redirect()->route('mahasiswa.reports.show', $report)
            ->with('success', 'Laporan berhasil dikirim');
    }

    public function show($id)
    {
        $report = Report::with(['user', 'facility', 'comments.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('mahasiswa.reports.show', compact('report'));
    }
}