<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\AbsensiHarian;
use App\Models\Keterlambatan;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAkademik;
use App\Models\AnggotaKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AkademikSiswaController extends Controller
{
    private function getStudentAndPeriod(Request $request)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        if (!$siswa) return [null, null, null];

        // Get selected period or active period
        $activePeriod = TahunAkademik::where('is_active', true)->first();
        $selectedPeriodId = $request->get('periode_id', $activePeriod->id ?? null);
        
        $allPeriods = TahunAkademik::orderBy('tahun_ajaran', 'desc')->orderBy('semester', 'desc')->get();

        return [$siswa, $selectedPeriodId, $allPeriods];
    }

    public function jadwal(Request $request)
    {
        [$siswa, $selectedPeriodId, $allPeriods] = $this->getStudentAndPeriod($request);
        if (!$siswa) return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan');

        // Find student's class for the selected period
        $anggotaKelas = AnggotaKelas::where('siswa_id', $siswa->id)
            ->where('tahun_akademik_id', $selectedPeriodId)
            ->first();

        $jadwals = collect();
        if ($anggotaKelas) {
            $jadwals = JadwalPelajaran::with(['mata_pelajaran', 'guru', 'tahun_akademik'])
                ->where('kelas_id', $anggotaKelas->kelas_id)
                ->where('tahun_akademik_id', $selectedPeriodId)
                ->get()
                ->groupBy('hari');
        }
        
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('siswa.jadwal', compact('jadwals', 'days', 'allPeriods', 'selectedPeriodId'));
    }

    public function absensi(Request $request)
    {
        [$siswa, $selectedPeriodId, $allPeriods] = $this->getStudentAndPeriod($request);
        if (!$siswa) return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan');

        $absensis = AbsensiHarian::with(['jadwal.mata_pelajaran', 'jadwal.guru'])
            ->where('siswa_id', $siswa->id)
            ->whereHas('jadwal', function($q) use ($selectedPeriodId) {
                $q->where('tahun_akademik_id', $selectedPeriodId);
            })
            ->get()
            ->sortByDesc(function($absensi) {
                return $absensi->tanggal . ' ' . ($absensi->jadwal->jam_mulai ?? '00:00:00');
            });

        return view('siswa.absensi', compact('absensis', 'allPeriods', 'selectedPeriodId'));
    }

    public function keterlambatan(Request $request)
    {
        [$siswa, $selectedPeriodId, $allPeriods] = $this->getStudentAndPeriod($request);
        if (!$siswa) return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan');

        $keterlambatans = Keterlambatan::where('siswa_id', $siswa->id)
            ->where('tahun_akademik_id', $selectedPeriodId)
            ->latest()
            ->get();
        
        $total_menit = $keterlambatans->sum('lama_menit');

        return view('siswa.keterlambatan', compact('keterlambatans', 'total_menit', 'allPeriods', 'selectedPeriodId'));
    }

    public function nilai(Request $request)
    {
        [$siswa, $selectedPeriodId, $allPeriods] = $this->getStudentAndPeriod($request);
        if (!$siswa) return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan');

        $nilais = Nilai::with(['mata_pelajaran', 'tahunAkademik'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_akademik_id', $selectedPeriodId)
            ->get();

        return view('siswa.nilai', compact('nilais', 'allPeriods', 'selectedPeriodId'));
    }
}
