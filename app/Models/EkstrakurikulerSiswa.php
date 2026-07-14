<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EkstrakurikulerSiswa extends Model
{
    protected $table = 'ekstrakurikuler_siswas';
    protected $fillable = ['siswa_id', 'kelas_id', 'tahun_akademik_id', 'nama_kegiatan', 'predikat', 'keterangan'];
}
