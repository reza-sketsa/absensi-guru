<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluationDetail extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'evaluation_details';

    protected $dates = ['deleted_at'];

    protected $fillable = [

        'evaluation_id',
        'student_id',
        'nilai'
    ];

    // INI YANG WAJIB ADA BIAR GAK ERROR LAGI:
    public function evaluation()
    {
        // Menghubungkan detail nilai balik ke header ujiannya
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
