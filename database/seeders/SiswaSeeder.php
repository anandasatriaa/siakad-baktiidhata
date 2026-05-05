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

        $tahunAkademik = \App\Models\TahunAkademik::where('is_active', true)->first();

        $siswa = \App\Models\Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => '12345',
            'nama_lengkap' => 'Andi Susanto',
            'jenis_kelamin' => 'L',
        ]);

        \App\Models\AnggotaKelas::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
            'tahun_akademik_id' => $tahunAkademik->id,
        ]);
    }
}
