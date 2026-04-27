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
            'nip' => 'required|unique:guru,nip',
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->nip . '@smkbaktiidhata.sch.id',
                'password' => Hash::make('smkbaktiidhata'),
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
            'nip' => 'required|unique:guru,nip,' . $guru->id,
            'nama_lengkap' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        DB::transaction(function () use ($request, $guru) {
            $guru->user->update([
                'name' => $request->nama_lengkap,
                'email' => $request->nip . '@smkbaktiidhata.sch.id',
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
