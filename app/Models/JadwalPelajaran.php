<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';

    protected $fillable = ['kelas_id', 'mapel_id', 'guru_id', 'tahun_akademik_id', 'hari', 'jam_mulai', 'jam_selesai'];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function mapel() { return $this->belongsTo(MataPelajaran::class, 'mapel_id'); }
    public function guru() { return $this->belongsTo(Guru::class); }
    public function tahunAkademik() { return $this->belongsTo(TahunAkademik::class); }
}
