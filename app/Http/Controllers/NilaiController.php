<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\JadwalPelajaran;
use App\Models\Siswa;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik']);

        if ($user->role == 'guru') {
            $query->whereHas('guru', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $jadwals = $query->get();
        $selected_jadwal = $request->jadwal_id;
        
        $siswas = [];
        $jadwal_info = null;
        if ($selected_jadwal) {
            $jadwal_info = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik'])->find($selected_jadwal);
            if ($jadwal_info) {
                $siswas = Siswa::where('kelas_id', $jadwal_info->kelas_id)->get();
                
                $existing_nilai = Nilai::where('jadwal_id', $selected_jadwal)
                    ->whereIn('siswa_id', $siswas->pluck('id'))
                    ->get()
                    ->keyBy('siswa_id');
                
                foreach ($siswas as $siswa) {
                    $siswa->nilai = $existing_nilai->get($siswa->id);
                }
            }
        }

        return view('admin.nilai.index', compact('jadwals', 'siswas', 'selected_jadwal', 'jadwal_info'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'nilai' => 'required|array',
        ]);

        $jadwal = JadwalPelajaran::find($request->jadwal_id);

        foreach ($request->nilai as $siswa_id => $data) {
            // Simple calculation for nilai_akhir: (Tugas + UTS + UAS) / 3
            $tugas = $data['nilai_tugas'] ?? 0;
            $uts = $data['nilai_uts'] ?? 0;
            $uas = $data['nilai_uas'] ?? 0;
            $akhir = ($tugas + $uts + $uas) / 3;

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'jadwal_id' => $request->jadwal_id,
                    'tahun_akademik_id' => $jadwal->tahun_akademik_id,
                ],
                [
                    'nilai_tugas' => $tugas,
                    'nilai_uts' => $uts,
                    'nilai_uas' => $uas,
                    'nilai_akhir' => $akhir,
                ]
            );
        }

        return redirect()->back()->with('success', 'Nilai berhasil disimpan');
    }
}
