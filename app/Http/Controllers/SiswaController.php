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
use Illuminate\Validation\Rule;
use App\Exports\SiswaExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

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
        $kelas = $this->kelasPeriodeAktif();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $tahunAkademik = $this->tahunAkademikAktif();

        if (!$tahunAkademik) {
            return back()
                ->withInput()
                ->withErrors(['kelas_id' => 'Tahun akademik aktif tidak ditemukan. Silakan aktifkan tahun akademik terlebih dahulu.']);
        }

        $request->validate([
            'nis' => 'required|unique:siswa,nis',
            'nama_lengkap' => 'required',
            'kelas_id' => [
                'required',
                Rule::exists('kelas', 'id')->where('tahun_akademik_id', $tahunAkademik->id),
            ],
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        DB::transaction(function () use ($request, $tahunAkademik) {
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
        $kelas = $this->kelasPeriodeAktif();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $tahunAkademik = $this->tahunAkademikAktif();

        if (!$tahunAkademik) {
            return back()
                ->withInput()
                ->withErrors(['kelas_id' => 'Tahun akademik aktif tidak ditemukan. Silakan aktifkan tahun akademik terlebih dahulu.']);
        }

        $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $siswa->id,
            'nama_lengkap' => 'required',
            'kelas_id' => [
                'required',
                Rule::exists('kelas', 'id')->where('tahun_akademik_id', $tahunAkademik->id),
            ],
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
        ]);

        DB::transaction(function () use ($request, $siswa, $tahunAkademik) {
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

    public function exportPdf()
    {
        $siswas = Siswa::with(['kelas.kelas'])->latest()->get();
        $tahun_akademik = $this->tahunAkademikAktif();

        $pdf = Pdf::loadView('admin.siswa.export.export-pdf', [
            'siswas' => $siswas,
            'tahun_akademik' => $tahun_akademik,
            'sekolah' => [
                'nama' => 'SMK BAKTI IDHATA',
                'alamat' => 'Jl. Melati No. 25 Cilandak',
                'kontak' => 'Telp. (021) 7500000 | Email: info@smkbaktiidhata.sch.id',
                'website' => 'www.smkbaktiidhata.sch.id',
            ],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Data_Siswa_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new SiswaExport, 'Data_Siswa_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    private function tahunAkademikAktif()
    {
        return TahunAkademik::where('is_active', true)->first();
    }

    private function kelasPeriodeAktif()
    {
        return Kelas::whereHas('tahunAkademik', function ($query) {
            $query->where('is_active', true);
        })->orderBy('nama_kelas')->get();
    }
}
