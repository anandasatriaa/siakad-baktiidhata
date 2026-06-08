<?php

namespace App\Exports;

use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAkademik;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NilaiExport implements FromView, ShouldAutoSize
{
    protected $siswa_id, $kelas_id, $periode_id;

    public function __construct($siswa_id, $kelas_id, $periode_id)
    {
        $this->siswa_id = $siswa_id;
        $this->kelas_id = $kelas_id;
        $this->periode_id = $periode_id;
    }

    public function view(): View
    {
        return view('guru.export.nilai-excel', [
            'siswa' => Siswa::find($this->siswa_id),
            'kelas' => Kelas::find($this->kelas_id),
            'tahun_akademik' => TahunAkademik::find($this->periode_id),
            'nilais' => Nilai::with('mata_pelajaran')
                ->where('siswa_id', $this->siswa_id)
                ->where('kelas_id', $this->kelas_id)
                ->where('tahun_akademik_id', $this->periode_id)
                ->get()
        ]);
    }
}
