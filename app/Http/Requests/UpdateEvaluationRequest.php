<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Secara teknis ini bisa return true karena otorisasi
        // sudah kita tangani pakai Gate::authorize() di Controller.
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_id'      => 'required|exists:subjects,id',
            'nama_penilaian' => 'required|string|max:30',
            'jenis'          => 'required|in:Tugas,UTS,UAS',
            'tanggal'        => 'required|date',
            'penilaian'      => 'required|array|min:1',
            'penilaian.*.student_id' => 'required|exists:students,id',
            'penilaian.*.nilai'      => 'nullable|numeric|min:0|max:100',
        ];
    }
}
