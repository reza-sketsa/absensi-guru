<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Evaluation;
use App\Models\EvaluationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{


    public function indexBlade()
    {
        $id_guru_login = 1; // nanti bisa diganti auth()->id()

        $students = Student::whereHas('classroom', function ($query) use ($id_guru_login) {
            $query->where('walas_id', $id_guru_login);
        })
            ->with(['classroom.teacher', 'evaluations.evaluation'])
            ->orderBy('nama', 'asc')
            ->get();

        return view('nilai.nilai', compact('students'));
    }

    public function index()
    {
        $students = Student::with('classroom')
            ->orderBy('nama', 'asc')
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Data ditemukan',
            'data'    => $students
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
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

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'data'    => $validator->errors()
            ], 422);
        }

        $student = Student::create($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil ditambahkan',
            'data'    => $student
        ], 201);
    }

    public function show(Student $student)
    {
        return response()->json([
            'status'  => true,
            'message' => 'Data ditemukan',
            'data'    => $student->load('classroom')
        ], 200);
    }

    public function update(Request $request, Student $student)
    {
        $rules = [
            'nama'         => 'required|string|max:100',
            'agama'        => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'jk'           => 'required|in:L,P',
            'tgl_lahir'    => 'required|date',
            'nis'          => 'required|unique:students,nis,' . $student->id,
            'alamat'       => 'required|string',
            'no_telp'      => 'required|string',
            'no_telp_ortu' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal',
                'data'    => $validator->errors()
            ], 422);
        }

        $student->update($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil diupdate',
            'data'    => $student
        ], 200);
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }


    public function input(Student $student)
    {
        $student->load(['classroom', 'evaluations']);

        $subjects    = Subject::all();
        $teachers    = Teacher::all();
        $evaluations = Evaluation::all();

        return view('nilai.input', compact('student', 'evaluations', 'subjects', 'teachers'));
    }

    public function storeNilai(Request $request)
    {
        $request->validate([
            'student_id'     => 'required|exists:students,id',
            'subject_id'     => 'required|exists:subjects,id',
            'teacher_id'     => 'required|exists:teachers,id',
            'jenis'          => 'required|string',
            'nama_penilaian' => 'required|string|max:255',
            'nilai'          => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {

            $evaluation = Evaluation::create([
                'subject_id'     => $request->subject_id,
                'teacher_id'     => $request->teacher_id,
                'schedule_id'    => 1, // pastikan ada di tabel schedules
                'jenis'          => $request->jenis,
                'nama_penilaian' => $request->nama_penilaian,
                'tanggal'        => now(),
            ]);

            EvaluationDetail::create([
                'student_id'    => $request->student_id,
                'evaluation_id' => $evaluation->id,
                'nilai'         => $request->nilai,
            ]);

            DB::commit();

            return redirect('/data')->with('success', 'Berhasil Simpan Nilai!');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}
