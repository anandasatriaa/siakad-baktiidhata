<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\TahunAkademik;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SiswaExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('admin.siswa.export.export-excel', [
            'siswas' => Siswa::with(['kelas.kelas'])->latest()->get(),
            'tahun_akademik' => TahunAkademik::where('is_active', true)->first(),
        ]);
    }
}
