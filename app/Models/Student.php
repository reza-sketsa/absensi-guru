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


    public function evaluations()
    {
        return $this->hasMany(EvaluationDetail::class);
    }
}
