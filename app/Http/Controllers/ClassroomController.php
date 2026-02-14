<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classroom = Classroom::with('teacher')
            ->orderBy('tingkat', 'asc')
            ->orderBy('paralel', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar kelas ditemukan',
            'data' => $classroom,
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
            'tingkat' => 'required|in:VII,VIII,IX',
            'paralel' => 'required|in:A,B,C,D,E,F,G,H',
            'walas_id' => 'required|exists:teachers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'data' => $validator->errors()
            ], 422);
        }

        $exists = Classroom::where('tingkat', $request->tingkat)
            ->where('paralel', $request->paralel)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Kelas ' . $request->tingkat . '-' . $request->paralel . ' sudah ada!'
            ], 422);
        }

        $classroom = Classroom::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Kelas berhasil dibuat',
            'data' => $classroom
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data kelas ditemukan',
            'data' => $classroom
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validator = Validator::make($request->all(), [
            'tingkat' => 'required|in:VII,VIII,IX',
            'paralel' => 'required|in:A,B,C,D,E,F,G,H',
            'walas_id' => 'required|exists:teachers,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors()
            ], 422);
        }

        $exists = Classroom::where('tingkat', $request->tingkat)
            ->where('paralel', $request->paralel)
            ->where('id', '!=', $classroom->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal update kelas ' . $request->tingkat . '-' . $request->paralel . ' sudah ada!'
            ], 422);
        }

        $classroom->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data kelas berhasil diupdate',
            'data' => $classroom
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kelas ' . $classroom->tingkat . '-' . $classroom->paralel . ' berhasil di hapus!'

        ], 200);
    }
}
