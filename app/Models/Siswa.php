<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = ['user_id', 'nis', 'nama_lengkap', 'jenis_kelamin', 'no_hp', 'alamat'];

    public function user() { return $this->belongsTo(User::class); }
    
    public function riwayatKelas() 
    { 
        return $this->hasMany(AnggotaKelas::class); 
    }

    public function kelas()
    {
        // Mendapatkan kelas saat ini (periode aktif)
        return $this->hasOne(AnggotaKelas::class)->whereHas('tahunAkademik', function($q) {
            $q->where('is_active', true);
        });
    }
}
