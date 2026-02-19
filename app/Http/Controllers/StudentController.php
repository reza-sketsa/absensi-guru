<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function indexBlade()
    {

        $id_guru_login = 1;

        $students = \App\Models\Student::whereHas('classroom', function ($query) use ($id_guru_login) {
            $query->where('walas_id', $id_guru_login);
        })
            ->with('classroom', 'evaluations.evaluation')
            ->orderBy('nama', 'asc')
            ->get();

        return view('nilai.nilai', compact('students'));
    }


    public function index()
    {

        $students = Student::with('classroom')->orderBy('nama', 'asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $students
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:100',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'jk' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'nis' => 'required|unique:students,nis',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'no_telp_ortu' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'data' => $validator->errors()
            ], 422);
        }

        $student = Student::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $student
        ], 201);
    }

    public function show(Student $student)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $student->load('classroom')
        ], 200);
    }

    public function update(Request $request, Student $student)
    {
        $rules = [
            'nama' => 'required|string|max:100',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'jk' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'nis' => 'required|unique:students,nis,' . $student->id,
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'no_telp_ortu' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data gagal di update',
                'data' => $validator->errors()
            ], 422);
        }

        $student->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di update',
            'data' => $student
        ], 200);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di delete',
        ], 200);
    }

    public function input(Student $student)
    {
        $student->load(['classroom', 'evaluations']);

        $subjects = \App\Models\Subject::all();
        $teachers = \App\Models\Teacher::all();
        $evaluations = \App\Models\Evaluation::all();

        return view('nilai.input', compact('student', 'evaluations', 'subjects', 'teachers'));
    }
    public function storeNilai(Request $request)
    {
        // Coba debug di sini, kalau pas klik simpan muncul data lu, berarti form OK
        // dd($request->all());

        // 1. Simpan Master Penilaian
        $evaluation = \App\Models\Evaluation::create([
            'subject_id'     => $request->subject_id,
            'teacher_id'     => $request->teacher_id,
            'schedule_id'    => 1, // Pastiin ini ada isinya biar gak error SQL lagi
            'jenis'          => $request->jenis,
            'nama_penilaian' => $request->nama_penilaian,
            'tanggal'        => now(),
        ]);

        // 2. Simpan Angka Nilainya
        if ($evaluation) {
            $detail = \App\Models\EvaluationDetail::create([
                'student_id'    => $request->student_id,
                'evaluation_id' => $evaluation->id,
                'nilai'         => $request->nilai,
            ]);

            // Cek apakah detail berhasil kesimpen
            if (!$detail) {
                return "Waduh, Master masuk tapi Detail Nilai gagal kesimpen, Bang!";
            }
        }

        return redirect('/data')->with('success', 'Berhasil Simpan Nilai!');
    }
}
