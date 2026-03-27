<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'teknisi') {
            return redirect()->route('teknisi.dashboard');
        } elseif ($user->role == 'supervisor') {
            return redirect()->route('supervisor.dashboard');
        } else {
            return redirect()->route('mahasiswa.dashboard');
        }
    }
}