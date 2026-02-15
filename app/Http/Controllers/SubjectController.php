<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{

    public function index()
    {
        $subjects = Subject::all();
        return response()->json([
            'status' => true,
            'message' => 'Daftar mata pelajaran',
            'data' => $subjects
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
        $rules = [
            'nama_mapel' => 'required|string|unique:subjects,nama_mapel',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
            ], 422);
        }
        $subjects = Subject::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Mapel berhasil ditambahkan',
            'data' => $subjects,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subjects)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subjects)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {

        if (!$subject) {
            return response()->json([
                'status' => false,
                'message' => 'Mapel tidak ada',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_mapel' => 'required|string|unique:subjects,nama_mapel,' . $subject->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors()
            ], 422);
        }

        $subject->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Mapel berhasil di update',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return response()->json([
            'status' => true,
            'message' => 'Mapel berhasil di hapus'
        ], 200);
    }
}
