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

class AcademicGuruController extends Controller
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
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran']);

        if ($guru) {
            $query->where('guru_id', $guru->id);
        }

        $jadwals = $query->get();
        $kelas_ids = $jadwals->pluck('kelas_id')->unique();
        
        $siswas = Siswa::with('kelas')
            ->whereIn('kelas_id', $kelas_ids)
            ->get()
            ->groupBy('kelas_id');

        return view('guru.data-siswa', compact('siswas', 'jadwals'));
    }

    public function rekapNilai(Request $request)
    {
        $guru = $this->getGuru();
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik']);

        if ($guru) {
            $query->where('guru_id', $guru->id);
        }

        $jadwals = $query->get();
        $selected_jadwal = $request->jadwal_id;

        $nilais = [];
        $jadwal_info = null;
        if ($selected_jadwal) {
            $jadwal_info = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik'])->find($selected_jadwal);
            $nilais = Nilai::with('siswa')
                ->where('jadwal_id', $selected_jadwal)
                ->get();
        }

        return view('guru.rekap-nilai', compact('jadwals', 'nilais', 'selected_jadwal', 'jadwal_info'));
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
