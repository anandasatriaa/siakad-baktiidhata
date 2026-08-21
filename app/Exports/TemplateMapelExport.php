<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemplateMapelExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'B-IND',
                'Bahasa Indonesia',
            ],
            [
                'M-MTK',
                'Matematika',
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Mapel',
            'Nama Mapel',
        ];
    }
}
