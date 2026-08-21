<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemplateSiswaExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            // Return dummy data to guide the user
            [
                '123456789',
                'Budi Santoso',
                'L',
                '081234567890',
                'Jl. Contoh No 123'
            ],
            [
                '987654321',
                'Siti Aminah',
                'P',
                '089876543210',
                'Jl. Demo No 456'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Lengkap',
            'Jenis Kelamin (L/P)',
            'No HP',
            'Alamat'
        ];
    }
}
