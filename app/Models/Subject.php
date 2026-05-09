<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['nama_mapel'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'subject_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'subject_id');
    }
}
