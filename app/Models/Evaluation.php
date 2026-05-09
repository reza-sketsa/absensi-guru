<?php

namespace App\Models;

use App\Models\EvaluationDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'schedule_id',
        'subject_id',
        'classroom_id',
        'teacher_id',
        'academic_year_id',
        'jenis',
        'nama_penilaian',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

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
