<?php

namespace App\Exports;

use App\Models\Nilai;
use App\Models\JadwalPelajaran;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NilaiExport implements FromView, ShouldAutoSize
{
    protected $mapel_id, $kelas_id, $periode_id;

    public function __construct($mapel_id, $kelas_id, $periode_id)
    {
        $this->mapel_id = $mapel_id;
        $this->kelas_id = $kelas_id;
        $this->periode_id = $periode_id;
    }

    public function view(): View
    {
        return view('guru.export.nilai-excel', [
            'jadwal' => JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik', 'guru'])
                ->where('mapel_id', $this->mapel_id)
                ->where('kelas_id', $this->kelas_id)
                ->where('tahun_akademik_id', $this->periode_id)
                ->first(),
            'nilais' => Nilai::with('siswa')
                ->where('mapel_id', $this->mapel_id)
                ->where('kelas_id', $this->kelas_id)
                ->where('tahun_akademik_id', $this->periode_id)
                ->get()
        ]);
    }
}
