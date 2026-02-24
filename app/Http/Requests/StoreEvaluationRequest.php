<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    // 1. Wajib diubah jadi true agar request diizinkan
    public function authorize(): bool
    {
        return true;
    }

    // 2. Pindahkan semua aturan validasi dari Controller ke sini
    public function rules(): array
    {
        return [
            'student_id' => 'nullable|exists:students,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'jenis' => 'required|in:Tugas,UTS,UAS',
            'nama_penilaian' => 'required|string|max:30',
            'tanggal' => 'required|date',
            'penilaian' => 'required|array|min:1',
            'penilaian.*.student_id' => 'required|exists:students,id',
            'penilaian.*.nilai' => 'nullable|numeric|min:0|max:100'
        ];
    }
}
