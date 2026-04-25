<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::insert([
            ['name' => 'Super Admin', 'email' => 'superadmin@smkbaktiidhata.sch.id', 'role' => 'super_admin', 'password' => bcrypt('password')],
            ['name' => 'Kepala Sekolah', 'email' => 'kepsek@smkbaktiidhata.sch.id', 'role' => 'kepala_sekolah', 'password' => bcrypt('password')],
            ['name' => 'Administrator', 'email' => 'admin@smkbaktiidhata.sch.id', 'role' => 'admin', 'password' => bcrypt('password')],
            ['name' => 'Guru Budi', 'email' => 'guru@smkbaktiidhata.sch.id', 'role' => 'guru', 'password' => bcrypt('password')],
            ['name' => 'Guru Piket Ayu', 'email' => 'piket@smkbaktiidhata.sch.id', 'role' => 'guru_piket', 'password' => bcrypt('password')],
            ['name' => 'Siswa Andi', 'email' => 'siswa@smkbaktiidhata.sch.id', 'role' => 'siswa', 'password' => bcrypt('password')],
        ]);
    }
}
