<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = [
        'user_id', 'nama', 'nuptk', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'nip', 'status_kepegawaian', 'jenis_ptk', 'gelar_depan', 'gelar_belakang',
        'jenjang', 'jurusan_prodi', 'sertifikasi', 'tmt_kerja', 'tugas_tambahan',
        'mengajar', 'jam_tugas_tambahan', 'jjm', 'total_jjm', 'jumlah_siswa',
        'kompetensi', 'nik', 'jabatan_ptk'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
