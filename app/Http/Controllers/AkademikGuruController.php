<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NilaiExport;

class AkademikGuruController extends Controller
{
    private function getGuru()
    {
        $user = Auth::user();
        if ($user->role == 'super_admin') return null;
        return Guru::where('user_id', $user->id)->first();
    }

    public function jadwal(Request $request)
    {
        $guru = $this->getGuru();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);

        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik']);

        if ($guru) {
            $query->where('guru_id', $guru->id);
        }

        if ($periode_id) {
            $query->where('tahun_akademik_id', $periode_id);
        }

        $jadwals = $query->get()->groupBy('hari');
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('guru.jadwal', compact('jadwals', 'days', 'periodes', 'periode_id', 'active_periode'));
    }

    public function dataSiswa(Request $request)
    {
        $guru = $this->getGuru();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);
        
        $query = JadwalPelajaran::query();
        if ($guru) {
            $query->where('guru_id', $guru->id);
        }
        if ($periode_id) {
            $query->where('tahun_akademik_id', $periode_id);
        }

        $jadwals = $query->with('kelas')->get();
        $kelas_ids = $jadwals->pluck('kelas_id')->unique();
        
        // Query melalui AnggotaKelas sesuai periode yang dipilih
        $siswas = Siswa::whereHas('riwayatKelas', function($q) use ($kelas_ids, $periode_id) {
            $q->whereIn('kelas_id', $kelas_ids);
            if ($periode_id) {
                $q->where('tahun_akademik_id', $periode_id);
            }
        })->with(['riwayatKelas' => function($q) use ($periode_id) {
            if ($periode_id) $q->where('tahun_akademik_id', $periode_id);
            $q->with('kelas');
        }])->get()->groupBy(function($s) {
            // Group berdasarkan nama kelas agar lebih informatif
            return $s->riwayatKelas->first()->kelas->nama_kelas ?? 'Tanpa Kelas';
        });

        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();

        return view('guru.data-siswa', compact('siswas', 'jadwals', 'periodes', 'periode_id'));
    }

    public function rekapNilai(Request $request)
    {
        $guru = $this->getGuru();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);

        // Ambil mapel & kelas yang diampu (dikompel agar tidak duplikat hari)
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran'])
            ->select('mapel_id', 'kelas_id', 'tahun_akademik_id')
            ->groupBy('mapel_id', 'kelas_id', 'tahun_akademik_id');

        if ($guru) {
            $query->where('guru_id', $guru->id);
        }
        
        if ($periode_id) {
            $query->where('tahun_akademik_id', $periode_id);
        }

        $ampu_mapel = $query->get();
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        
        $selected_mapel = $request->mapel_id;
        $selected_kelas = $request->kelas_id;

        $nilais = [];
        $info = null;

        if ($selected_mapel && $selected_kelas) {
            $info = $ampu_mapel->first(function($item) use ($selected_mapel, $selected_kelas) {
                return $item->mapel_id == $selected_mapel && $item->kelas_id == $selected_kelas;
            });
            
            if ($info) {
                // Ambil semua siswa di kelas tersebut pada periode tersebut
                $siswas_kelas = \App\Models\AnggotaKelas::with('siswa')
                    ->where('kelas_id', $selected_kelas)
                    ->where('tahun_akademik_id', $periode_id)
                    ->get();

                // Ambil nilai berdasarkan Mapel, Kelas, dan Periode
                $existing_nilais = Nilai::where('mapel_id', $selected_mapel)
                    ->where('kelas_id', $selected_kelas)
                    ->where('tahun_akademik_id', $periode_id)
                    ->get()
                    ->keyBy('siswa_id');

                foreach ($siswas_kelas as $ak) {
                    $nilai = $existing_nilais->get($ak->siswa_id);
                    $nilais[] = (object)[
                        'siswa' => $ak->siswa,
                        'nilai_tugas' => $nilai->nilai_tugas ?? null,
                        'nilai_uts' => $nilai->nilai_uts ?? null,
                        'nilai_uas' => $nilai->nilai_uas ?? null,
                        'nilai_akhir' => $nilai->nilai_akhir ?? null,
                    ];
                }
            }
        }

        return view('guru.rekap-nilai', compact('ampu_mapel', 'nilais', 'selected_mapel', 'selected_kelas', 'info', 'periodes', 'periode_id'));
    }

    public function exportPdf(Request $request)
    {
        $mapel_id = $request->mapel_id;
        $kelas_id = $request->kelas_id;
        $periode_id = $request->periode_id;

        $info = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik', 'guru'])
            ->where('mapel_id', $mapel_id)
            ->where('kelas_id', $kelas_id)
            ->where('tahun_akademik_id', $periode_id)
            ->firstOrFail();

        $nilais = Nilai::with('siswa')
            ->where('mapel_id', $mapel_id)
            ->where('kelas_id', $kelas_id)
            ->where('tahun_akademik_id', $periode_id)
            ->get();

        $pdf = Pdf::loadView('guru.export.nilai-pdf', [
            'jadwal' => $info,
            'nilais' => $nilais
        ]);
        
        return $pdf->download('Rekap_Nilai_' . $info->mata_pelajaran->nama_mapel . '_' . $info->kelas->nama_kelas . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $mapel_id = $request->mapel_id;
        $kelas_id = $request->kelas_id;
        $periode_id = $request->periode_id;

        $info = JadwalPelajaran::with(['kelas', 'mata_pelajaran'])
            ->where('mapel_id', $mapel_id)
            ->where('kelas_id', $kelas_id)
            ->where('tahun_akademik_id', $periode_id)
            ->firstOrFail();

        return Excel::download(new NilaiExport($mapel_id, $kelas_id, $periode_id), 'Rekap_Nilai_' . $info->mata_pelajaran->nama_mapel . '_' . $info->kelas->nama_kelas . '.xlsx');
    }
}
