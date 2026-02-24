<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationDetail extends Model
{
    protected $table = 'evaluation_details'; // Sesuaikan sama nama tabel di DB

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
