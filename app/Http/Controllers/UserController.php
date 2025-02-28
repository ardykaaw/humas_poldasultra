<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        $totalUsers = User::count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalStaff = User::where('role', 'user')->count();
        $newUsers = User::whereDate('created_at', today())->count();

        return view('users.index', compact('users', 'totalUsers', 'totalAdmin', 'totalStaff', 'newUsers'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|unique:users',
            'pangkat' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user'
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'nip.required' => 'NIP harus diisi',
            'nip.unique' => 'NIP sudah digunakan',
            'pangkat.required' => 'Pangkat harus diisi',
            'jabatan.required' => 'Jabatan harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'nip' => $request->nip,
                'pangkat' => $request->pangkat,
                'jabatan' => $request->jabatan,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan pengguna')
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|unique:users,nip,' . $user->id,
            'pangkat' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,user'
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'nip.required' => 'NIP harus diisi',
            'nip.unique' => 'NIP sudah digunakan',
            'pangkat.required' => 'Pangkat harus diisi',
            'jabatan.required' => 'Jabatan harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 8 karakter',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid'
        ]);

        try {
            $data = $request->only(['name', 'nip', 'pangkat', 'jabatan', 'email', 'role']);
            
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengguna')
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            if ($user->id === Auth::id()) {
                return redirect()->route('users.index')
                    ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan saat menghapus pengguna');
        }
    }
} 