<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $fillable = ['judul', 'konten', 'tanggal', 'penulis_id'];

    public function penulis() { return $this->belongsTo(User::class, 'penulis_id'); }
}
