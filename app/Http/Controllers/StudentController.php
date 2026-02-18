<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    // FRONTEND
    public function index()
    {
        $students = Student::with('classroom')->orderBy('nama','asc')->get();
        return view('admin.dashboard', compact('students'));
    }

    // API
    public function apiIndex()
    {
        $students = Student::with('classroom')->orderBy('nama','asc')->get();
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
                'data' => $validator->errors()
            ], 422);
        }

        $student = Student::create($request->all());

        return response()->json([
            'status' => true,
            'data' => $student
        ], 201);
    }

    public function show(Student $student)
    {
        return response()->json([
            'status' => true,
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
                'data' => $validator->errors()
            ], 422);
        }

        $student->update($request->all());

        return response()->json([
            'status' => true,
            'data' => $student
        ], 200);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json(['status' => true], 200);
    }
}
