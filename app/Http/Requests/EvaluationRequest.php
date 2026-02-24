<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_penilaian' => 'sometimes|required|string|max:30',
            'jenis' => 'sometimes|required|in:Tugas,UTS,UAS',
            'tanggal' => 'sometimes|required|date',
            'penilaian' => 'sometimes|required|array',
            'penilaian.*.student_id' => 'sometimes|required|exists:students,id',
            'penilaian.*.nilai' => 'sometimes|required|numeric'
        ];
    }
}
