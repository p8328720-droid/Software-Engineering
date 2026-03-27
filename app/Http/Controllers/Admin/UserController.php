<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'student_id' => 'nullable|string|max:20|unique:users',
            'phone' => 'nullable|string|max:15',
            'faculty' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'role' => 'required|in:mahasiswa,teknisi,supervisor,admin',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'faculty' => $request->faculty,
            'major' => $request->major,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'student_id' => 'nullable|string|max:20|unique:users,student_id,' . $id,
            'phone' => 'nullable|string|max:15',
            'faculty' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'role' => 'required|in:mahasiswa,teknisi,supervisor,admin',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'phone' => $request->phone,
            'faculty' => $request->faculty,
            'major' => $request->major,
            'role' => $request->role,
        ];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus');
    }
}