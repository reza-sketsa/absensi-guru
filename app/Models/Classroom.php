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
}
