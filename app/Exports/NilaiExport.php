<?php

namespace App\Exports;

use App\Models\Nilai;
use App\Models\JadwalPelajaran;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NilaiExport implements FromView, ShouldAutoSize
{
    protected $jadwal_id;

    public function __construct($jadwal_id)
    {
        $this->jadwal_id = $jadwal_id;
    }

    public function view(): View
    {
        return view('guru.export.nilai-excel', [
            'jadwal' => JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik', 'guru'])->find($this->jadwal_id),
            'nilais' => Nilai::with('siswa')->where('jadwal_id', $this->jadwal_id)->get()
        ]);
    }
}
