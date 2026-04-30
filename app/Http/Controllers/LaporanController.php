<?php

namespace App\Http\Controllers;

use App\Models\AbsensiHarian;
use App\Models\Keterlambatan;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function absensiPelanggaran(Request $request)
    {
        $kelas = Kelas::all();
        $selected_kelas = $request->kelas_id;
        $tanggal_mulai = $request->tanggal_mulai ?? date('Y-m-01'); // Awal bulan
        $tanggal_selesai = $request->tanggal_selesai ?? date('Y-m-d');

        $query_siswa = Siswa::with('kelas');
        if ($selected_kelas) {
            $query_siswa->where('kelas_id', $selected_kelas);
        }
        $siswas = $query_siswa->get();

        foreach ($siswas as $siswa) {
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
        }

        return view('admin.laporan.index', compact('kelas', 'siswas', 'selected_kelas', 'tanggal_mulai', 'tanggal_selesai'));
    }
}
