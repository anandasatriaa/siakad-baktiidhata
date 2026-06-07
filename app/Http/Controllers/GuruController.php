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
            'nip' => 'nullable|unique:guru,nip',
            'nik' => 'required|numeric|unique:guru,nik',
            'nama' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'status_kepegawaian' => 'nullable|in:GTY/PTY,Guru Honor Sekolah',
            'jenis_ptk' => 'nullable|in:Kepala Sekolah,Guru,Tenaga Kependidikan',
            'tanggal_lahir' => 'nullable|date',
            'tmt_kerja' => 'nullable|date',
            'jam_tugas_tambahan' => 'nullable|integer',
            'jjm' => 'nullable|integer',
            'total_jjm' => 'nullable|integer',
            'jumlah_siswa' => 'nullable|integer',
        ]);

        DB::transaction(function () use ($request) {
            $emailPrefix = $request->nik;
            $fullName = trim(($request->gelar_depan ? $request->gelar_depan . ' ' : '') . $request->nama . ($request->gelar_belakang ? ', ' . $request->gelar_belakang : ''));
            
            $user = User::create([
                'name' => $fullName,
                'email' => $emailPrefix . '@smkbaktiidhata.sch.id',
                'password' => Hash::make('smkbaktiidhata'),
                'role' => 'guru',
            ]);

            $data = $request->all();
            $data['user_id'] = $user->id;
            Guru::create($data);
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
            'nip' => 'nullable|unique:guru,nip,' . $guru->id,
            'nik' => 'required|numeric|unique:guru,nik,' . $guru->id,
            'nama' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'status_kepegawaian' => 'nullable|in:GTY/PTY,Guru Honor Sekolah',
            'jenis_ptk' => 'nullable|in:Kepala Sekolah,Guru,Tenaga Kependidikan',
            'tanggal_lahir' => 'nullable|date',
            'tmt_kerja' => 'nullable|date',
            'jam_tugas_tambahan' => 'nullable|integer',
            'jjm' => 'nullable|integer',
            'total_jjm' => 'nullable|integer',
            'jumlah_siswa' => 'nullable|integer',
        ]);

        DB::transaction(function () use ($request, $guru) {
            $emailPrefix = $request->nik;
            $fullName = trim(($request->gelar_depan ? $request->gelar_depan . ' ' : '') . $request->nama . ($request->gelar_belakang ? ', ' . $request->gelar_belakang : ''));
            
            $guru->user->update([
                'name' => $fullName,
                'email' => $emailPrefix . '@smkbaktiidhata.sch.id',
            ]);

            if ($request->filled('password')) {
                $guru->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $guru->update($request->all());
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil diperbarui');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            if ($guru->user) {
                $guru->user->delete();
            }
            $guru->delete();
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil dihapus');
    }
}
