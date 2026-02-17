<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::with(['schedule.teacher', 'schedule.subject', 'details.student'])
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data riwayat absen ditemukan',
            'data' => $attendance
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*.student_id' => 'required|exists:students,id',
            'absensi.*.status' => 'required|in:Sakit,Izin,Alpa,Hadir',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors()
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            // Buat Master
            $attendance = Attendance::create([
                'schedule_id' => $request->schedule_id,
                'tanggal' => $request->tanggal,
            ]);

            // Buat Detail
            foreach ($request->absensi as $item) {
                $attendance->details()->create([
                    'student_id' => $item['student_id'],
                    'status'     => $item['status']
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Absensi berhasil disimpan',
                'data' => $attendance->load('details.student')
            ], 201);
        });
    }

    public function show(Attendance $attendance)
    {
        return response()->json([
            'status' => true,
            'data' => $attendance->load(['schedule.teacher', 'schedule.subject', 'details.student'])
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*.student_id' => 'required|exists:students,id',
            'absensi.*.status' => 'required|in:Sakit,Izin,Alpa,Hadir',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'data' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request, $attendance) {
            // Update Master
            $attendance->update([
                'schedule_id' => $request->schedule_id,
                'tanggal' => $request->tanggal,
            ]);

            // Refresh Detail: Hapus lama, buat baru
            $attendance->details()->delete();
            foreach ($request->absensi as $item) {
                $attendance->details()->create([
                    'student_id' => $item['student_id'],
                    'status'     => $item['status']
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Absensi berhasil diperbarui',
                'data' => $attendance->load('details.student')
            ], 200);
        });
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data absensi berhasil dihapus'
        ], 200);
    }
}
