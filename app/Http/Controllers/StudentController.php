<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{


    public function indexBlade()
    {
        // Eager loading 'classroom' dan 'classroom.teacher' (Wali Kelas)
        $students = Student::with(['classroom.teacher'])
            ->orderBy('nama')
            ->paginate(15);

        return view('nilai.nilai', compact('students'));
    }

    public function index(Request $request)
    {
        $students = Student::with('classroom')
            ->when($request->search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            })
            ->orderBy('nama', 'asc')
            ->paginate(20);

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil dimuat',
            'data'    => $students
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'         => 'required|string|max:100',
            'agama'        => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'jk'           => 'required|in:L,P',
            'tgl_lahir'    => 'required|date',
            'nis'          => 'required|unique:students,nis',
            'alamat'       => 'required|string',
            'no_telp'      => 'required|string',
            'no_telp_ortu' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $student = Student::create($request->all());
        return response()->json(['status' => true, 'data' => $student], 201);
    }

    public function show(Student $student)
    {
        return response()->json([
            'status'  => true,
            'data'    => $student->load('classroom')
        ], 200);
    }



    public function update(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'nama'         => 'required|string|max:100',
            'agama'        => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'jk'           => 'required|in:L,P',
            'tgl_lahir'    => 'required|date',
            'nis'          => 'required|unique:students,nis,' . $student->id,
            'alamat'       => 'required|string',
            'no_telp'      => 'required|string',
            'no_telp_ortu' => 'required|string',
            'classroom_id' => 'required|exists:classrooms,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $student->update($request->all());

        return response()->json(
            [
                'status' => true,
                'message' => 'Data diperbarui',
                'data' => $student
            ],
            200
        );
    }


    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
