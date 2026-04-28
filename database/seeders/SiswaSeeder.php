<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswaUser = \App\Models\User::where('email', 'siswa@smkbaktiidhata.sch.id')->first();
        $kelas = \App\Models\Kelas::first();

        \App\Models\Siswa::create([
            'user_id' => $siswaUser->id,
            'kelas_id' => $kelas->id,
            'nis' => '12345',
            'nama_lengkap' => 'Andi Susanto',
            'jenis_kelamin' => 'L',
        ]);
    }
}
