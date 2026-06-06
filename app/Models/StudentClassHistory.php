<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClassHistory extends Model
{
    protected $fillable = [
        'student_id',
        'from_classroom_id',
        'to_classroom_id',
        'academic_year_id',
        'jenis',
        'keterangan',
        'processed_by',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromClassroom()
    {
        return $this->belongsTo(Classroom::class, 'from_classroom_id');
    }

    public function toClassroom()
    {
        return $this->belongsTo(Classroom::class, 'to_classroom_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
