<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher = Teacher::with(['user', 'school'])->orderBy('nama_guru', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data guru ditemukan',
            'data' => $teacher
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'nama_guru' => 'required|string|max:100',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'nip' => 'required|unique:teachers,nip',
            'tgl_lahir' => 'required|date',
            'jk' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'school_id' => 'required|exists:schools,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal bang!',
                'data' => $validator->errors()
            ], 422);
        }

        $teacher = Teacher::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Guru baru berhasil ditambahkan',
            'data' => $teacher
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return response()->json([
            'status' => true,
            'data' => $teacher->load(['user', 'school'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'nama_guru' => 'required|string|max:100',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Khonghucu',
            'nip' => 'required|unique:teachers,nip,' . $teacher->id,
            'tgl_lahir' => 'required|date',
            'jk' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'school_id' => 'required|exists:schools,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors()
            ], 422);
        }

        $teacher->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data guru berhasil diupdate',
            'data' => $teacher
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data Guru ' . $teacher->nama_guru . ' berhasil dihapus!'

        ], 200);
    }
}
