<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id', 
        'mapel_id', 
        'kelas_id', 
        'tahun_akademik_id', 
        'nilai_tugas', 
        'nilai_uts', 
        'nilai_uas', 
        'nilai_akhir'
    ];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function mata_pelajaran() { return $this->belongsTo(MataPelajaran::class, 'mapel_id'); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function tahunAkademik() { return $this->belongsTo(TahunAkademik::class); }
}
