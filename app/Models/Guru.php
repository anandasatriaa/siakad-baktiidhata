<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = ['user_id', 'nip', 'nama_lengkap', 'jenis_kelamin', 'no_hp', 'alamat'];
}
