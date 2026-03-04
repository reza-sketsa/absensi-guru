<?php

namespace App\Models;

use App\Models\AttendanceDetail;
use Illuminate\Database\Eloquent\Model;


class Attendance extends Model
{
    protected $fillable = [
        'schedule_id',
        'academic_year_id',
        'tanggal'
    ];

    public function details()
    {
        return $this->hasMany(AttendanceDetail::class, 'attendance_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
