<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\EvaluationDetail;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{


    public function indexBlade()
    {
        // Eager loading 'classroom' dan 'classroom.teacher' (Wali Kelas)
        $students = Student::with(['classroom.teacher'])
            ->orderBy('nama')
            ->paginate(15);

        $trashCount = EvaluationDetail::onlyTrashed()->count();

        return view('nilai.nilai', compact('students', 'trashCount'));
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

    public function store(StudentRequest $request)
    {
        $students = Student::create($request->validated());

        return response()->json(['status' => true, 'data' => $students], 201);
    }

    public function show(Student $students)
    {
        return response()->json([
            'status'  => true,
            'data'    => $students->load('classroom')
        ], 200);
    }



    public function update(StudentRequest $request, Student $students)
    {
        $students->update($request->validated());

        return response()->json(
            [
                'status' => true,
                'message' => 'Data diperbarui',
                'data' => $students
            ],
            200
        );
    }


    public function destroy(Student $students)
    {
        $students->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
