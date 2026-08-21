<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class MapelImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                if (empty($row['kode_mapel']) || empty($row['nama_mapel'])) {
                    continue;
                }

                if (MataPelajaran::where('kode_mapel', $row['kode_mapel'])->exists()) {
                    continue;
                }

                MataPelajaran::create([
                    'kode_mapel' => $row['kode_mapel'],
                    'nama_mapel' => $row['nama_mapel'],
                ]);
            }
        });
    }
}
