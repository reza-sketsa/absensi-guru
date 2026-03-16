<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index($kelas_id)
    {
        $kelas = DB::table('classrooms')->where('id', $kelas_id)->first();
        $students = DB::table('students')->where('classroom_id', $kelas_id)->get();

        return view('admin.kelas.students', compact('kelas', 'students'));
    }

    public function store(Request $request, $kelas_id)
    {
        $request->validate([
            'nama' => 'required',
            'nis' => 'required|unique:students,nis',
        ]);

        DB::table('students')->insert([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'classroom_id' => $kelas_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke kelas!');
    }
}
