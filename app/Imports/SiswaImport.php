<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Exception;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                // Pastikan nis dan nama lengkap ada
                if (empty($row['nis']) || empty($row['nama_lengkap'])) {
                    continue;
                }

                // Cek apakah NIS sudah ada, skip jika ada
                if (Siswa::where('nis', $row['nis'])->exists()) {
                    continue;
                }

                // Buat User
                $user = User::create([
                    'name' => $row['nama_lengkap'],
                    'email' => $row['nis'] . '@smkbaktiidhata.sch.id',
                    'password' => Hash::make('smkbaktiidhata'),
                    'password_plain' => 'smkbaktiidhata',
                    'role' => 'siswa',
                ]);

                // Normalisasi jenis kelamin (default ke L jika tidak dikenali)
                $jk = strtoupper(substr(trim($row['jenis_kelamin_lp']), 0, 1));
                if (!in_array($jk, ['L', 'P'])) {
                    $jk = 'L';
                }

                // Buat Siswa
                Siswa::create([
                    'user_id' => $user->id,
                    'nis' => $row['nis'],
                    'nama_lengkap' => $row['nama_lengkap'],
                    'jenis_kelamin' => $jk,
                    'no_hp' => $row['no_hp'] ?? null,
                    'alamat' => $row['alamat'] ?? null,
                ]);
            }
        });
    }
}
