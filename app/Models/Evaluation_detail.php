<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation_detail extends Model
{
    protected $fillable = [
        'evaluation_id',
        'student_id',
        'nilai'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
