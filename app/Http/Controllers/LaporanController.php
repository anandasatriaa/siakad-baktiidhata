<?php

namespace App\Http\Controllers;

use App\Models\AbsensiHarian;
use App\Models\Keterlambatan;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Exports\LaporanAbsensiExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function absensiKeterlambatan(Request $request)
    {
        $data = $this->getRekapData($request);
        return view('admin.laporan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getRekapData($request);
        $data['sekolah'] = [
            'nama' => 'SMK BAKTI IDHATA',
            'alamat' => 'Jl. Melati No. 25 Cilandak',
            'kontak' => 'Telp. (021) 7500000 | Email: info@smkbaktiidhata.sch.id',
            'website' => 'www.smkbaktiidhata.sch.id',
        ];

        $pdf = Pdf::loadView('admin.laporan.export.export-pdf', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Absensi_Keterlambatan_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getRekapData($request);
        return Excel::download(new LaporanAbsensiExport($data), 'Laporan_Absensi_Keterlambatan_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    private function getRekapData(Request $request)
    {
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);
        $tahun_akademik = \App\Models\TahunAkademik::find($periode_id) ?? $active_periode;
        
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        $kelas = Kelas::where('tahun_akademik_id', $periode_id)->get();
        
        $selected_kelas = $request->kelas_id;
        $info_kelas = $selected_kelas ? Kelas::find($selected_kelas) : null;
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

        return compact(
            'kelas',
            'siswas',
            'selected_kelas',
            'info_kelas',
            'tanggal_mulai',
            'tanggal_selesai',
            'periodes',
            'periode_id',
            'tahun_akademik'
        );
    }
}
