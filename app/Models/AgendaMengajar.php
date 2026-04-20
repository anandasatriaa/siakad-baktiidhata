<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaMengajar extends Model
{
    protected $fillable = ['guru_id', 'jadwal_id', 'tanggal', 'materi', 'keterangan'];

    public function guru() { return $this->belongsTo(Guru::class); }
    public function jadwal() { return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id'); }
}
