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
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }
 
    public function create()
    {
        return view('admin.users.create');
    }
 
    public function store(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:15',
            'role'     => 'required|in:mahasiswa,teknisi,admin',
            'password' => 'required|string|min:6|confirmed',
        ];
 
        // Validasi tambahan per role
        if ($request->role === 'mahasiswa') {
            $rules['student_id'] = 'nullable|string|max:20|unique:users';
            $rules['faculty']    = 'nullable|string|max:255';
            $rules['major']      = 'nullable|string|max:255';
        } elseif ($request->role === 'teknisi') {
            $rules['student_id'] = 'nullable|string|max:20|unique:users'; // NIP
            $rules['major']      = 'nullable|string|max:255';             // spesialisasi
        }
        // admin: tidak ada field tambahan
 
        $request->validate($rules);
 
        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'role'       => $request->role,
            'password'   => Hash::make($request->password),
            // Reset field yang tidak relevan untuk role ini
            'student_id' => in_array($request->role, ['mahasiswa', 'teknisi']) ? $request->student_id : null,
            'faculty'    => $request->role === 'mahasiswa' ? $request->faculty : null,
            'major'      => in_array($request->role, ['mahasiswa', 'teknisi']) ? $request->major : null,
        ];
 
        User::create($data);
 
        return redirect()->route('admin.users')
            ->with('success', 'User berhasil ditambahkan');
    }
 
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
 
    public function update(Request $request, User $user)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:15',
            'role'     => 'required|in:mahasiswa,teknisi,admin',
            'password' => 'nullable|string|min:6|confirmed',
        ];
 
        // Validasi tambahan per role
        if ($request->role === 'mahasiswa') {
            $rules['student_id'] = 'nullable|string|max:20|unique:users,student_id,' . $user->id;
            $rules['faculty']    = 'nullable|string|max:255';
            $rules['major']      = 'nullable|string|max:255';
        } elseif ($request->role === 'teknisi') {
            $rules['student_id'] = 'nullable|string|max:20|unique:users,student_id,' . $user->id; // NIP
            $rules['major']      = 'nullable|string|max:255'; // spesialisasi
        }
        // admin: tidak ada field tambahan
 
        $request->validate($rules);
 
        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role'  => $request->role,
            // Set field sesuai role, null-kan yang tidak relevan
            'student_id' => in_array($request->role, ['mahasiswa', 'teknisi']) ? $request->student_id : null,
            'faculty'    => $request->role === 'mahasiswa' ? $request->faculty : null,
            'major'      => in_array($request->role, ['mahasiswa', 'teknisi']) ? $request->major : null,
        ];
 
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
 
        $user->update($data);
 
        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diperbarui');
    }
 
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }
 
        $user->delete();
 
        return back()->with('success', 'User berhasil dihapus');
    }
}