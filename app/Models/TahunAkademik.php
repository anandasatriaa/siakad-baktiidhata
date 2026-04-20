<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $fillable = ['tahun_ajaran', 'semester', 'is_active'];
}
