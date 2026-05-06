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
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);

        // Ambil mata pelajaran & kelas yang diampu (dikelompokkan agar tidak duplikat hari)
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran'])
            ->select('mapel_id', 'kelas_id', 'tahun_akademik_id')
            ->groupBy('mapel_id', 'kelas_id', 'tahun_akademik_id');

        if ($user->role == 'guru') {
            $query->whereHas('guru', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        if ($periode_id) {
            $query->where('tahun_akademik_id', $periode_id);
        }

        $ampu_mapel = $query->get();
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        
        $selected_mapel = $request->mapel_id;
        $selected_kelas = $request->kelas_id;
        
        $siswas = [];
        $info = null;

        if ($selected_mapel && $selected_kelas) {
            // Cari info mapel & kelas dari koleksi ampu_mapel
            $info = $ampu_mapel->first(function($item) use ($selected_mapel, $selected_kelas) {
                return $item->mapel_id == $selected_mapel && $item->kelas_id == $selected_kelas;
            });
            
            if ($info) {
                // Ambil siswa melalui AnggotaKelas
                $siswas = Siswa::whereHas('riwayatKelas', function($q) use ($selected_kelas, $periode_id) {
                    $q->where('kelas_id', $selected_kelas)
                      ->where('tahun_akademik_id', $periode_id);
                })->get();
                
                $existing_nilai = Nilai::where('mapel_id', $selected_mapel)
                    ->where('kelas_id', $selected_kelas)
                    ->where('tahun_akademik_id', $periode_id)
                    ->whereIn('siswa_id', $siswas->pluck('id'))
                    ->get()
                    ->keyBy('siswa_id');
                
                foreach ($siswas as $siswa) {
                    $siswa->nilai = $existing_nilai->get($siswa->id);
                }
            }
        }

        return view('admin.nilai.index', compact('ampu_mapel', 'siswas', 'selected_mapel', 'selected_kelas', 'info', 'periodes', 'periode_id', 'active_periode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'nilai' => 'required|array',
        ]);

        foreach ($request->nilai as $siswa_id => $data) {
            $tugas = $data['nilai_tugas'] ?? 0;
            $uts = $data['nilai_uts'] ?? 0;
            $uas = $data['nilai_uas'] ?? 0;
            $akhir = ($tugas + $uts + $uas) / 3;

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'mapel_id' => $request->mapel_id,
                    'kelas_id' => $request->kelas_id,
                    'tahun_akademik_id' => $request->tahun_akademik_id,
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
