<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNotIn('role', ['siswa', 'guru'])->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = [
            'admin' => 'Admin',
            'guru_piket' => 'Guru Piket',
            'kepala_sekolah' => 'Kepala Sekolah',
            'super_admin' => 'Super Admin'
        ];
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'guru_piket', 'kepala_sekolah', 'super_admin'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Akun berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        if (in_array($user->role, ['siswa', 'guru'])) {
            return redirect()->route('users.index')->with('error', 'Akun siswa/guru tidak dapat diedit di sini');
        }

        $roles = [
            'admin' => 'Admin',
            'guru_piket' => 'Guru Piket',
            'kepala_sekolah' => 'Kepala Sekolah',
            'super_admin' => 'Super Admin'
        ];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'guru_piket', 'kepala_sekolah', 'super_admin'])],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Akun berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if (in_array($user->role, ['siswa', 'guru'])) {
            return redirect()->route('users.index')->with('error', 'Akun siswa/guru tidak dapat dihapus di sini');
        }

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus');
    }
}
