<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Truncate existing data to start fresh
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tables = [
            'absensi_harian', 'agenda_mengajar', 'anggota_kelas', 'keterlambatan', 'nilai',
            'pengumuman', 'jadwal_pelajaran', 'kelas', 'mata_pelajaran', 'guru', 'siswa',
            'tahun_akademik', 'users'
        ];
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        // 2. Tahun Akademik
        $tahun_akademik_id = DB::table('tahun_akademik')->insertGetId([
            'tahun_ajaran' => '2025/2026',
            'semester' => 'Ganjil',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 3. Users (1 Admin & 1 Super Admin)
        $superadmin_id = DB::table('users')->insertGetId([
            'name' => 'Super Administrator',
            'email' => 'superadmin@smkbaktiidhata.sch.id',
            'password' => Hash::make('smkbaktiidhata'),
            'role' => 'super_admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $admin_id = DB::table('users')->insertGetId([
            'name' => 'Administrator',
            'email' => 'admin@smkbaktiidhata.sch.id',
            'password' => Hash::make('smkbaktiidhata'),
            'role' => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 4. Guru & Users (Kepala Sekolah, Guru Piket, Guru Biasa)
        $guru_ids = [];
        $guru_data = [
            ['nama' => 'Dr. H. Ahmad Kepala, M.Pd', 'nik' => '1234567890123450', 'jk' => 'L', 'mapel' => 'Manajemen Sekolah', 'role' => 'kepala_sekolah', 'jenis_ptk' => 'Kepala Sekolah'],
            ['nama' => 'Siti Aminah, S.Pd', 'nik' => '1234567890123451', 'jk' => 'P', 'mapel' => 'Bimbingan Konseling', 'role' => 'guru_piket', 'jenis_ptk' => 'Guru'],
            ['nama' => 'Budi Santoso, S.Kom', 'nik' => '1234567890123452', 'jk' => 'L', 'mapel' => 'Matematika', 'role' => 'guru', 'jenis_ptk' => 'Guru'],
            ['nama' => 'Agus Pratama, S.Ing', 'nik' => '1234567890123453', 'jk' => 'L', 'mapel' => 'Bahasa Inggris', 'role' => 'guru', 'jenis_ptk' => 'Guru'],
            ['nama' => 'Dewi Lestari, S.Si', 'nik' => '1234567890123454', 'jk' => 'P', 'mapel' => 'Fisika', 'role' => 'guru', 'jenis_ptk' => 'Guru'],
            ['nama' => 'Rudi Hermawan, S.Si', 'nik' => '1234567890123455', 'jk' => 'L', 'mapel' => 'Kimia', 'role' => 'guru', 'jenis_ptk' => 'Guru'],
        ];

        foreach ($guru_data as $g) {
            $user_id = DB::table('users')->insertGetId([
                'name' => $g['nama'],
                'email' => $g['nik'] . '@smkbaktiidhata.sch.id',
                'password' => Hash::make('smkbaktiidhata'),
                'role' => $g['role'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $guru_id = DB::table('guru')->insertGetId([
                'user_id' => $user_id,
                'nama' => $g['nama'],
                'nik' => $g['nik'],
                'jenis_kelamin' => $g['jk'],
                'status_kepegawaian' => 'GTY/PTY',
                'jenis_ptk' => $g['jenis_ptk'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $guru_ids[] = $guru_id;
        }

        // 5. Mata Pelajaran (5 Mapel)
        $mapel_ids = [];
        foreach ($guru_data as $idx => $g) {
            $mapel_id = DB::table('mata_pelajaran')->insertGetId([
                'kode_mapel' => 'MPL00' . ($idx + 1),
                'nama_mapel' => $g['mapel'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $mapel_ids[] = $mapel_id;
        }

        // 6. Kelas (5 Kelas)
        $kelas_ids = [];
        $nama_kelas = ['10 IPA 1', '10 IPA 2', '11 IPS 1', '11 IPS 2', '12 BAHASA 1'];
        foreach ($nama_kelas as $idx => $nama) {
            $kelas_id = DB::table('kelas')->insertGetId([
                'nama_kelas' => $nama,
                'tingkat' => (int) substr($nama, 0, 2),
                'tahun_akademik_id' => $tahun_akademik_id,
                'wali_kelas_id' => $guru_ids[$idx], // Each guru is wali kelas of 1 class
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $kelas_ids[] = $kelas_id;
        }

        // 7. Siswa & Users (5 Siswa)
        $siswa_ids = [];
        $siswa_data = [
            ['nama' => 'Ahmad Fikri', 'nis' => '10001', 'jk' => 'L'],
            ['nama' => 'Bunga Citra', 'nis' => '10002', 'jk' => 'P'],
            ['nama' => 'Cahya Putra', 'nis' => '10003', 'jk' => 'L'],
            ['nama' => 'Dina Mariana', 'nis' => '10004', 'jk' => 'P'],
            ['nama' => 'Eko Prasetyo', 'nis' => '10005', 'jk' => 'L'],
        ];

        foreach ($siswa_data as $idx => $s) {
            $user_id = DB::table('users')->insertGetId([
                'name' => $s['nama'],
                'email' => $s['nis'] . '@smkbaktiidhata.sch.id',
                'password' => Hash::make('smkbaktiidhata'),
                'role' => 'siswa',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $siswa_id = DB::table('siswa')->insertGetId([
                'user_id' => $user_id,
                'nis' => $s['nis'],
                'nama_lengkap' => $s['nama'],
                'jenis_kelamin' => $s['jk'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $siswa_ids[] = $siswa_id;

            // Assign Siswa to Kelas (1 siswa per kelas for simplicity)
            DB::table('anggota_kelas')->insert([
                'siswa_id' => $siswa_id,
                'kelas_id' => $kelas_ids[$idx],
                'tahun_akademik_id' => $tahun_akademik_id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 8. Jadwal Pelajaran (5 Jadwal)
        $jadwal_ids = [];
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        foreach ($kelas_ids as $idx => $k_id) {
            $jadwal_id = DB::table('jadwal_pelajaran')->insertGetId([
                'kelas_id' => $k_id,
                'mapel_id' => $mapel_ids[$idx],
                'guru_id' => $guru_ids[$idx],
                'tahun_akademik_id' => $tahun_akademik_id,
                'hari' => $hari[$idx],
                'jam_mulai' => '07:30:00',
                'jam_selesai' => '09:00:00',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $jadwal_ids[] = $jadwal_id;
        }

        // 9. Absensi Harian (5 Absensi)
        foreach ($siswa_ids as $idx => $s_id) {
            DB::table('absensi_harian')->insert([
                'siswa_id' => $s_id,
                'jadwal_id' => $jadwal_ids[$idx],
                'tanggal' => $now->toDateString(),
                'status' => 'Hadir',
                'pencatat_id' => $guru_ids[$idx], // guru mencatat
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 10. Keterlambatan (5 Keterlambatan)
        foreach ($siswa_ids as $idx => $s_id) {
            DB::table('keterlambatan')->insert([
                'siswa_id' => $s_id,
                'tahun_akademik_id' => $tahun_akademik_id,
                'tanggal' => clone $now->subDays($idx),
                'lama_menit' => 15 + ($idx * 5),
                'alasan' => 'Macet di jalan raya',
                'pencatat_id' => $guru_ids[$idx],
                'created_at' => clone $now->subDays($idx),
                'updated_at' => clone $now->subDays($idx),
            ]);
        }

        // 11. Agenda Mengajar (5 Agenda)
        foreach ($jadwal_ids as $idx => $j_id) {
            DB::table('agenda_mengajar')->insert([
                'guru_id' => $guru_ids[$idx],
                'jadwal_id' => $j_id,
                'tahun_akademik_id' => $tahun_akademik_id,
                'tanggal' => clone $now->subDays($idx),
                'materi' => 'Bab ' . ($idx + 1) . ' Pengenalan Konsep',
                'keterangan' => 'Siswa sangat antusias',
                'created_at' => clone $now->subDays($idx),
                'updated_at' => clone $now->subDays($idx),
            ]);
        }

        // 12. Nilai (5 Nilai)
        foreach ($siswa_ids as $idx => $s_id) {
            DB::table('nilai')->insert([
                'siswa_id' => $s_id,
                'mapel_id' => $mapel_ids[$idx],
                'kelas_id' => $kelas_ids[$idx],
                'tahun_akademik_id' => $tahun_akademik_id,
                'nilai_tugas' => 85.00 + $idx,
                'nilai_uts' => 80.00 + $idx,
                'nilai_uas' => 90.00 + $idx,
                'nilai_akhir' => 85.00 + $idx,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 13. Pengumuman (5 Pengumuman)
        $pengumuman_titles = [
            'Libur Nasional Idul Fitri',
            'Persiapan Ujian Tengah Semester',
            'Pendaftaran Ekstrakurikuler Baru',
            'Rapat Wali Murid Akhir Tahun',
            'Perubahan Jadwal Pelajaran'
        ];
        foreach ($pengumuman_titles as $idx => $title) {
            DB::table('pengumuman')->insert([
                'judul' => $title,
                'konten' => 'Detail mengenai ' . strtolower($title) . ' akan segera diinformasikan kepada seluruh siswa dan wali murid. Harap mempersiapkan diri dengan baik.',
                'tanggal' => clone $now->subDays($idx),
                'penulis_id' => $admin_id,
                'tahun_akademik_id' => $tahun_akademik_id,
                'created_at' => clone $now->subDays($idx),
                'updated_at' => clone $now->subDays($idx),
            ]);
        }
    }
}
