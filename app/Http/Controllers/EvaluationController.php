<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Evaluation::with(['details.student']);

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        return response()->json([
            'status' => true,
            'data' => $query->latest()->get()
        ]);
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
            'schedule_id' => 'required|exists:schedules,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'jenis' => 'required|in:Tugas,UTS,UAS',
            'nama_penilaian' => 'required|string|max:30',
            'tanggal' => 'required|date',
            'penilaian' => 'required|array',
            'penilaian.*.student_id' => 'required|exists:students,id',
            'penilaian.*.nilai' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors()
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            $evaluation = Evaluation::create([
                'schedule_id' => $request->schedule_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'jenis' => $request->jenis,
                'nama_penilaian' => $request->nama_penilaian,
                'tanggal' => $request->tanggal
            ]);

            foreach ($request->penilaian as $item) {
                $evaluation->details()->create([
                    'student_id' => $item['student_id'],
                    'nilai' => $item['nilai'],
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Nilai berhasil diinput',
                    'data' => $evaluation->load('details.student')
                ], 200);
            }
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        $validator  = Validator::make($request->all(), [
            'nama_penilaian' => 'required|string|max:30',
            'jenis' => 'required|in:Tugas,UTS,UAS',
            'tanggal' => 'required|date',
            'penilaian' => 'required|array',
            'penilaian.*.student_id' => 'required|exists:students,id',
            'penilaian.*.nilai' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors()
            ], 422);
        }

        return DB::transaction(function () use ($request, $evaluation) {

            $evaluation->update([
                'nama_penilaian' => $request->nama_penilaian,
                'jenis' => $request->jenis,
                'tanggal' => $request->tanggal,
            ]);

            $evaluation->details()->delete();

            foreach ($request->penilaian as $item) {
                $evaluation->details()->create([
                    'student_id' => $item['student_id'],
                    'nilai' => $item['nilai'],
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Nilai berhasil di update',
                'true' => $evaluation->load('details.student')
            ], 200);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
    {
        return DB::transaction(function () use ($evaluation) {

            $evaluation->details()->delete();
            $evaluation->delete();

            return response()->json([
                'status' => true,
                'message' => 'Nilai berhasil dihapus',
                'data' => $evaluation->load('details.student'),
            ]);
        });
    }
}
