<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = ['nama_kelas', 'wali_kelas_id'];

    public function wali_kelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }
}
