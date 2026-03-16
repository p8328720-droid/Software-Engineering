<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
{
 $report = Report::create([
   'user_id'=>auth()->id(),
   'facility_id'=>$request->facility,
   'deskripsi'=>$request->deskripsi,
   'status'=>'pending'
 ]);

 return redirect()->back();
}
}
