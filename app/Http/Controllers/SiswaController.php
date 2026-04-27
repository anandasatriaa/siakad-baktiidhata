<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with('kelas')->latest()->get();
        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswas,nis',
            'nisn' => 'nullable|unique:siswas,nisn',
            'nama_lengkap' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
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
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        });

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil ditambahkan');
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => 'required|unique:siswas,nis,' . $siswa->id,
            'nisn' => 'nullable|unique:siswas,nisn,' . $siswa->id,
            'nama_lengkap' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
        ]);

        DB::transaction(function () use ($request, $siswa) {
            $siswa->user->update([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $siswa->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $siswa->update([
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);
        });

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {
            $siswa->user->delete();
            $siswa->delete();
        });

        return redirect()->route('siswa.index')->with('success', 'Data Siswa berhasil dihapus');
    }
}
