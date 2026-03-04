<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Models\Subject;

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
    public function store(SubjectRequest $request)
    {
        $subjects = Subject::create($request->validated());

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
    public function update(SubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());

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
