<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guru = \App\Models\Guru::first();
        \App\Models\Kelas::create([
            'nama_kelas' => 'X RPL 1',
            'wali_kelas_id' => $guru->user_id, // refers to user id which is safe.
        ]);
    }
}
