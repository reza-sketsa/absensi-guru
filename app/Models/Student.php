<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = "students";
    protected $fillable = [
        'nama',
        'agama',
        'jk',
        'tgl_lahir',
        'nis',
        'alamat',
        'no_telp',
        'no_telp_ortu',
        'classroom_id',
    ];
}
