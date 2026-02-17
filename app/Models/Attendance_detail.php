<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance_detail extends Model
{
    protected $fillable = [
        'attendance_id',
        'student_id',
        'status'
    ];

    public function student()
    {
        // Relasi ini bilang kalau satu baris detail absen dimiliki oleh satu siswa
        return $this->belongsTo(Student::class, 'student_id');
    }
}
