<?php

namespace App\Exports;

use App\Models\Guru;
use App\Models\TahunAkademik;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GuruExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('admin.guru.export.export-excel', [
            'gurus' => Guru::latest()->get(),
            'tahun_akademik' => TahunAkademik::where('is_active', true)->first(),
        ]);
    }
}
