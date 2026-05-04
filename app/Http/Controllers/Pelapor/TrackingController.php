<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('mahasiswa.tracking');
    }

    public function show($code)
    {
        $report = Report::with(['facility', 'statusHistory'])
            ->where('id', $code)
            ->first();
            
        return response()->json($report);
    }
}