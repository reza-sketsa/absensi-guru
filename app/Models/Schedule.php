<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'classroom_id',
        'hari',
        'jam_mulai',
        'jam_habis'
    ];

    public function teacher()
    {
        // Schedule ini 'milik' seorang Teacher lewat teacher_id
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
}
