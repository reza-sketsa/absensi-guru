<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // nanti bisa dipindah ke policy
    }

    public function rules(): array
    {
        return [
            'nama'         => 'required|string|max:100',
            'agama'        => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'jk'           => 'required|in:L,P',
            'tgl_lahir'    => 'required|date',
            'nis'          => 'required|unique:students,nis',
            'alamat'       => 'required|string',
            'no_telp'      => 'required|string',
            'no_telp_ortu' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id'
        ];
    }
}
