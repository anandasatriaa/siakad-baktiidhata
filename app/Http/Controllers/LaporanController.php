<?php

namespace App\Http\Controllers;

use App\Models\AbsensiHarian;
use App\Models\Keterlambatan;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function absensiKeterlambatan(Request $request)
    {
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);
        
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        $kelas = Kelas::where('tahun_akademik_id', $periode_id)->get();
        
        $selected_kelas = $request->kelas_id;
        $tanggal_mulai = $request->tanggal_mulai ?? date('Y-m-01'); // Awal bulan
        $tanggal_selesai = $request->tanggal_selesai ?? date('Y-m-d');

        // Query siswa melalui AnggotaKelas karena di tabel siswa tidak ada kelas_id
        $query_anggota = \App\Models\AnggotaKelas::with(['siswa', 'kelas'])
            ->where('tahun_akademik_id', $periode_id);

        if ($selected_kelas) {
            $query_anggota->where('kelas_id', $selected_kelas);
        }

        $anggota_kelas = $query_anggota->get();
        $siswas = [];

        foreach ($anggota_kelas as $ak) {
            $siswa = $ak->siswa;
            $siswa->nama_kelas = $ak->kelas->nama_kelas; // Simpan nama kelas untuk view

            // Get attendance summary
            $absensi = AbsensiHarian::where('siswa_id', $siswa->id)
                ->whereBetween('tanggal', [$tanggal_mulai, $tanggal_selesai])
                ->get();
            
            $siswa->rekap_absensi = [
                'Hadir' => $absensi->where('status', 'Hadir')->count(),
                'Sakit' => $absensi->where('status', 'Sakit')->count(),
                'Izin' => $absensi->where('status', 'Izin')->count(),
                'Alpa' => $absensi->where('status', 'Alpa')->count(),
            ];

            // Get lateness summary
            $keterlambatan = Keterlambatan::where('siswa_id', $siswa->id)
                ->whereBetween('tanggal', [$tanggal_mulai, $tanggal_selesai])
                ->get();
            
            $siswa->total_keterlambatan = $keterlambatan->count();
            $siswa->total_menit = $keterlambatan->sum('lama_menit');
            
            $siswas[] = $siswa;
        }

        return view('admin.laporan.index', compact('kelas', 'siswas', 'selected_kelas', 'tanggal_mulai', 'tanggal_selesai', 'periodes', 'periode_id'));
    }
}
