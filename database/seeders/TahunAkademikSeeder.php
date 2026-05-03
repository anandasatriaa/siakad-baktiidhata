<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TahunAkademik::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true]);
        \App\Models\TahunAkademik::create(['tahun_ajaran' => '2026/2027', 'semester' => 'Genap', 'is_active' => false]);
    }
}
