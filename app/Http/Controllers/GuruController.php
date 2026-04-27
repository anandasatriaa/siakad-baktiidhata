<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::latest()->get();
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'nullable|unique:gurus,nip',
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'guru',
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil ditambahkan');
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip' => 'nullable|unique:gurus,nip,' . $guru->id,
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $guru->user_id,
        ]);

        DB::transaction(function () use ($request, $guru) {
            $guru->user->update([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $guru->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $guru->update([
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil diperbarui');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            $guru->user->delete();
            $guru->delete();
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil dihapus');
    }
}
