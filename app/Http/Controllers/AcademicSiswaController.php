<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\AbsensiHarian;
use App\Models\Keterlambatan;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicSiswaController extends Controller
{
    public function jadwal()
    {
        $user = Auth::user();
        
        // Find the student record associated with the user
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan');

        $jadwals = JadwalPelajaran::with(['mata_pelajaran', 'guru', 'tahun_akademik'])
            ->where('kelas_id', $siswa->kelas_id)
            ->get()
            ->groupBy('hari');
        
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('siswa.jadwal', compact('jadwals', 'days'));
    }

    public function absensi()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan');

        $absensis = AbsensiHarian::with(['jadwal.mata_pelajaran', 'jadwal.guru'])
            ->where('siswa_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->sortByDesc(function($absensi) {
                return $absensi->tanggal . ' ' . ($absensi->jadwal->jam_mulai ?? '00:00:00');
            });

        return view('siswa.absensi', compact('absensis'));
    }

    public function keterlambatan()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan');

        $keterlambatans = Keterlambatan::where('siswa_id', $siswa->id)
            ->latest()
            ->get();
        
        $total_menit = $keterlambatans->sum('lama_menit');

        return view('siswa.keterlambatan', compact('keterlambatans', 'total_menit'));
    }

    public function nilai()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan');

        $nilais = Nilai::with(['jadwal.mata_pelajaran', 'tahun_akademik'])
            ->where('siswa_id', $siswa->id)
            ->get();

        return view('siswa.nilai', compact('nilais'));
    }
}
