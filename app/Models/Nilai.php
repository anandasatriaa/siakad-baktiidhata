<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $fillable = ['siswa_id', 'jadwal_id', 'tahun_akademik_id', 'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function jadwal() { return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id'); }
    public function tahunAkademik() { return $this->belongsTo(TahunAkademik::class); }
}
