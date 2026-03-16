<?php

namespace App\Models;

use App\Models\EvaluationDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    protected $casts = [
        'tanggal' => 'date',
    ];

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function details()
    {
        return $this->hasMany(EvaluationDetail::class, 'evaluation_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
}
