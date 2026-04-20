<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiHarian extends Model
{
    protected $fillable = ['siswa_id', 'tanggal', 'status', 'keterangan', 'pencatat_id'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function pencatat() { return $this->belongsTo(User::class, 'pencatat_id'); }
}
