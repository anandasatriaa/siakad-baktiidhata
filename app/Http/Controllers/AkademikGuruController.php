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

    public function jadwal()
    {
        $guru = $this->getGuru();
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik']);

        if ($guru) {
            $query->where('guru_id', $guru->id);
        }

        $jadwals = $query->get()->groupBy('hari');
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('guru.jadwal', compact('jadwals', 'days'));
    }

    public function dataSiswa()
    {
        $guru = $this->getGuru();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        
        $query = JadwalPelajaran::query();
        if ($guru) {
            $query->where('guru_id', $guru->id);
        }
        if ($active_periode) {
            $query->where('tahun_akademik_id', $active_periode->id);
        }

        $jadwals = $query->with('kelas')->get();
        $kelas_ids = $jadwals->pluck('kelas_id')->unique();
        
        // Fix: Query melalui AnggotaKelas karena Siswa tidak punya kelas_id langsung
        $siswas = Siswa::whereHas('riwayatKelas', function($q) use ($kelas_ids, $active_periode) {
            $q->whereIn('kelas_id', $kelas_ids);
            if ($active_periode) {
                $q->where('tahun_akademik_id', $active_periode->id);
            }
        })->with(['riwayatKelas' => function($q) use ($active_periode) {
            if ($active_periode) $q->where('tahun_akademik_id', $active_periode->id);
        }, 'riwayatKelas.kelas'])->get()->groupBy(function($s) {
            return $s->riwayatKelas->first()->kelas_id ?? 'Tanpa Kelas';
        });

        return view('guru.data-siswa', compact('siswas', 'jadwals'));
    }

    public function rekapNilai(Request $request)
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

        $jadwals = $query->get();
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        $selected_jadwal = $request->jadwal_id;

        $nilais = [];
        $jadwal_info = null;
        if ($selected_jadwal) {
            $jadwal_info = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik'])->find($selected_jadwal);
            
            // Mengambil semua siswa yang ada di kelas tersebut pada periode tersebut
            $siswas_kelas = \App\Models\AnggotaKelas::with('siswa')
                ->where('kelas_id', $jadwal_info->kelas_id)
                ->where('tahun_akademik_id', $jadwal_info->tahun_akademik_id)
                ->get();

            // Mengambil nilai yang sudah ada
            $existing_nilais = Nilai::where('jadwal_id', $selected_jadwal)->get()->keyBy('siswa_id');

            // Menggabungkan data (agar siswa yang belum punya nilai tetap muncul di rekap)
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

        return view('guru.rekap-nilai', compact('jadwals', 'nilais', 'selected_jadwal', 'jadwal_info', 'periodes', 'periode_id'));
    }

    public function exportPdf($jadwal_id)
    {
        $jadwal = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik', 'guru'])->findOrFail($jadwal_id);
        $nilais = Nilai::with('siswa')->where('jadwal_id', $jadwal_id)->get();

        $pdf = Pdf::loadView('guru.export.nilai-pdf', compact('jadwal', 'nilais'));
        return $pdf->download('Nilai_' . $jadwal->mata_pelajaran->nama_mapel . '_' . $jadwal->kelas->nama_kelas . '.pdf');
    }

    public function exportExcel($jadwal_id)
    {
        $jadwal = JadwalPelajaran::with(['kelas', 'mata_pelajaran'])->findOrFail($jadwal_id);
        return Excel::download(new NilaiExport($jadwal_id), 'Nilai_' . $jadwal->mata_pelajaran->nama_mapel . '_' . $jadwal->kelas->nama_kelas . '.xlsx');
    }
}
