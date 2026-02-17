<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedule = Schedule::with(['teacher', 'subject', 'classroom'])->get();

        return response()->json([
            'status' => true,
            'message' => 'Jadwal mata pelajaran ditemukan',
            'data' => $schedule
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
            'teacher_id'   => 'required|exists:teachers,id',
            'subject_id'   => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'hari'         => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'    => 'required',
            'jam_habis'    => 'required|after:jam_mulai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validator gagal, silahkan cek',
                'data' => $validator->errors()
            ], 422);
        }

        $schedule = Schedule::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $schedule
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        return response()->json([
            'status' => true,
            'message' => 'Detail jadwal',
            'data' => $schedule->load(['teacher', 'subject', 'classroom'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id'   => 'required|exists:teachers,id',
            'subject_id'   => 'required|exists:subjects,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'hari'         => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'    => 'required',
            'jam_habis'    => 'required|after:jam_mulai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Jadwal gagal ditambah',
                'data' => $validator->errors()
            ], 422);
        }

        $schedule->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $schedule
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->json([
            'status' => true,
            'message' => 'Jadwal berhasil dihapus secara permanen'
        ], 200);
    }
}
