<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'student_id' => 'required|string|max:20|unique:users',
            'phone' => 'required|string|max:15',
            'faculty' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'faculty' => $request->faculty,
            'major' => $request->major,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        // Login
        Auth::login($user);

        // Regenerate session
        $request->session()->regenerate();

        // Redirect ke dashboard
        return redirect()->route('dashboard');
    }
}