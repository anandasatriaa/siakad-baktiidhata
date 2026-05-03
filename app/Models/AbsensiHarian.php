<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiHarian extends Model
{
    protected $table = 'absensi_harian';

    protected $fillable = ['siswa_id', 'jadwal_id', 'tanggal', 'status', 'keterangan', 'pencatat_id'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function jadwal() { return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id'); }
    public function pencatat() { return $this->belongsTo(User::class, 'pencatat_id'); }
}
