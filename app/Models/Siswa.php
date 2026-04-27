<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = ['user_id', 'kelas_id', 'nis', 'nisn', 'nama_lengkap', 'jenis_kelamin', 'no_hp', 'alamat'];

    public function user() { return $this->belongsTo(User::class); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
}
