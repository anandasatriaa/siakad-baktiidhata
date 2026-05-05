<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\AnggotaKelas;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        // Eager load kelas (AnggotaKelas) dan kelas asli di dalamnya
        $siswas = Siswa::with(['kelas.kelas'])->latest()->get();
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
            'nis' => 'required|unique:siswa,nis',
            'nama_lengkap' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        DB::transaction(function () use ($request) {
            $tahunAkademik = TahunAkademik::where('is_active', true)->first();
            
            if (!$tahunAkademik) {
                throw new \Exception('Tahun akademik aktif tidak ditemukan. Silakan aktifkan tahun akademik terlebih dahulu.');
            }

            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->nis . '@smkbaktiidhata.sch.id',
                'password' => Hash::make('smkbaktiidhata'),
                'role' => 'siswa',
            ]);

            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);

            // Simpan ke tabel anggota_kelas
            AnggotaKelas::create([
                'siswa_id' => $siswa->id,
                'kelas_id' => $request->kelas_id,
                'tahun_akademik_id' => $tahunAkademik->id,
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
            'nis' => 'required|unique:siswa,nis,' . $siswa->id,
            'nama_lengkap' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        DB::transaction(function () use ($request, $siswa) {
            $tahunAkademik = TahunAkademik::where('is_active', true)->first();

            if (!$tahunAkademik) {
                throw new \Exception('Tahun akademik aktif tidak ditemukan.');
            }

            $siswa->user->update([
                'name' => $request->nama_lengkap,
                'email' => $request->nis . '@smkbaktiidhata.sch.id',
            ]);

            if ($request->filled('password')) {
                $siswa->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $siswa->update([
                'nis' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);

            // Update atau create anggota kelas untuk tahun akademik aktif
            AnggotaKelas::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'tahun_akademik_id' => $tahunAkademik->id,
                ],
                [
                    'kelas_id' => $request->kelas_id,
                ]
            );
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
