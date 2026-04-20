<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MataPelajaran::insert([
            ['kode_mapel' => 'MP01', 'nama_mapel' => 'Pemrograman Web'],
            ['kode_mapel' => 'MP02', 'nama_mapel' => 'Matematika Terapan'],
            ['kode_mapel' => 'MP03', 'nama_mapel' => 'Bahasa Inggris'],
        ]);
    }
}
