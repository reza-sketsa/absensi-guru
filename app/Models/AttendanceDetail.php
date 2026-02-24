<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
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

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
}
