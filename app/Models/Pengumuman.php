<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = ['judul', 'konten', 'tanggal', 'penulis_id', 'tahun_akademik_id'];

    public function penulis() { return $this->belongsTo(User::class, 'penulis_id'); }
    public function tahunAkademik() { return $this->belongsTo(TahunAkademik::class); }
}
