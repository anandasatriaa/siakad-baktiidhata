<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruUser1 = \App\Models\User::where('email', 'guru@smk.id')->first();
        \App\Models\Guru::create([
            'user_id' => $guruUser1->id,
            'nip' => '198001012005011003',
            'nama_lengkap' => 'Guru Budi, M.Kom',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
        ]);
        
        $guruUser2 = \App\Models\User::where('email', 'piket@smk.id')->first();
        \App\Models\Guru::create([
            'user_id' => $guruUser2->id,
            'nip' => '198502022010012004',
            'nama_lengkap' => 'Guru Piket Ayu, S.Pd',
            'jenis_kelamin' => 'P',
            'no_hp' => '089876543210',
        ]);
    }
}
