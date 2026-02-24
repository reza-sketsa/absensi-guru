<?php

namespace App\Models;

use App\Models\EvaluationDetail;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'schedule_id',
        'subject_id',
        'teacher_id',
        'academic_year_id',
        'jenis',
        'nama_penilaian',
        'tanggal'
    ];

    public function details()
    {
        return $this->hasMany(EvaluationDetail::class, 'evaluation_id');
    }
}
