<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ========== MAHASISWA ==========

    public function showMahasiswaLoginForm()
    {
        return view('auth.mahasiswa.login');
    }

    public function mahasiswaLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role !== 'mahasiswa') {
                Auth::logout();

                return back()->withErrors(['email' => 'Akun ini bukan untuk portal mahasiswa.']);
            }
            $request->session()->regenerate();

            return redirect()->route('mahasiswa.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function showMahasiswaRegisterForm()
    {
        return view('auth.mahasiswa.register');
    }

    public function mahasiswaRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'student_id' => 'required|string|max:20|unique:users',
            'phone' => 'required|string|max:15',
            'faculty' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'faculty' => $request->faculty,
            'major' => $request->major,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        return redirect()->route('mahasiswa.login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ========== TEKNISI ==========

    public function showTeknisiLoginForm()
    {
        return view('auth.teknisi.login');
    }

    public function teknisiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role !== 'teknisi') {
                Auth::logout();

                return back()->withErrors(['email' => 'Akun ini bukan untuk portal teknisi.']);
            }
            $request->session()->regenerate();

            return redirect()->route('teknisi.dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function showTeknisiRegisterForm()
    {
        return abort(403, 'Function was disabled.');

        // return view('auth.teknisi.register');
    }

    public function teknisiRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:15',
            // ✅ FIX: nama field di form adalah 'specialization',
            // tapi kolom DB adalah 'major' — validasi tetap pakai nama field form
            'specialization' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            // ✅ FIX: map 'specialization' dari form ke kolom 'major' di DB
            'major' => $request->specialization,
            'password' => Hash::make($request->password),
            'role' => 'teknisi',
        ]);

        return redirect()->route('teknisi.login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ========== ADMIN ==========

    public function showAdminLoginForm()
    {
        return view('auth.admin.login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            if ($user->role !== 'admin') {
                Auth::logout();

                return back()->withErrors(['email' => 'Akun ini bukan untuk portal admin.']);
            }
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function showAdminRegisterForm()
    {
        return abort(403, 'Function was disabled.');
        // return view('auth.admin.register');
    }

    public function adminRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:15',
            'division' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ========== LOGOUT ==========

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
