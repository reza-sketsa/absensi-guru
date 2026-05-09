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
        'tgl_lahir',
        'alamat',
        'no_telp',
        'school_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }
}
