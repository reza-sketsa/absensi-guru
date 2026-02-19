<?php

namespace App\Models;

use App\Models\EvaluationDetail;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = "students";

    protected $fillable = [
        'nama',
        'agama',
        'jk',
        'tgl_lahir',
        'nis',
        'alamat',
        'no_telp',
        'no_telp_ortu',
        'classroom_id',
    ];
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    // Tambahkan ini di dalam class Student
    public function evaluations()
    {
        // Sesuaikan dengan nama model nilai lu, misal Evaluation atau Grade
        return $this->hasMany(EvaluationDetail::class);
    }
}
