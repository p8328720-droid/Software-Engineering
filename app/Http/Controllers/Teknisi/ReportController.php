<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function show($id)
    {
        $report = Report::with(['user', 'facility', 'comments'])
            ->findOrFail($id);
            
        return view('teknisi.reports.show', compact('report'));
    }
}