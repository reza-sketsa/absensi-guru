<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['tingkat', 'paralel', 'walas_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'walas_id');
    }

    public function getNamaKelasAttribute()
    {
        return $this->tingkat . ' ' . $this->paralel;
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'classroom_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'classroom_id');
    }
}
