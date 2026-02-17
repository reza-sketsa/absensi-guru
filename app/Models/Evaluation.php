<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'schedule_id',
        'subject_id',
        'teacher_id',
        'jenis',
        'nama_penilaian',
        'tanggal'
    ];

    public function details()
    {
        return $this->hasMany(Evaluation_detail::class, 'evaluation_id');
    }
}
