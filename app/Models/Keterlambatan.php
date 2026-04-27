<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keterlambatan extends Model
{
    protected $table = 'keterlambatan';

    protected $fillable = ['siswa_id', 'tanggal', 'lama_menit', 'alasan', 'pencatat_id'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function pencatat() { return $this->belongsTo(User::class, 'pencatat_id'); }
}
