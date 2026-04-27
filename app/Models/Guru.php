<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = ['user_id', 'nip', 'nama_lengkap', 'jenis_kelamin', 'no_hp', 'alamat'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
