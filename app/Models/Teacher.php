<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'nama_guru',
        'agama',
        'nip',
        'jk',
        'alamat',
        'no_telp',
        'school_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
