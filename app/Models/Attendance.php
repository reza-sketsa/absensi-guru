<?php

namespace App\Models;

use App\Models\Attendance_detail;
use Illuminate\Database\Eloquent\Model;


class Attendance extends Model
{
    protected $fillable = [
        'schedule_id',
        'tanggal'
    ];

    public function details()
    {
        // Relasi One-to-Many ke tabel attendance_details
        return $this->hasMany(Attendance_detail::class, 'attendance_id');
    }
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
