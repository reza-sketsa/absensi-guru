<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{

    // ===============================
    // VIEW ABSENSI (BUAT HALAMAN)
    // ===============================
    public function index(Request $request)
    {

        $scheduleId = $request->get('schedule_id');
        if (!$scheduleId)
            return redirect()->back()->with('error', 'pilih jadwal');

        $schedule = Schedule::with(['subject', 'classroom'])->findOrFail($scheduleId);
        $students = Student::where('classroom_id', $schedule->classroom_id)
            ->orderBy('nama', 'asc')
            ->get();

        return view('absensi.absen', compact('students', 'schedule'));
    }


    // ===============================
    // API RIWAYAT (JSON)
    // ===============================
    public function apiIndex()
    {
        $attendance = Attendance::with([
            'schedule.teacher',
            'schedule.subject',
            'details.student'
        ])
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data riwayat absen ditemukan',
            'data' => $attendance
        ], 200);
    }

    public function create($schedule_id)
    {
        $schedule = Schedule::with(['classroom', 'subject'])->findOrFail($schedule_id);

        $students = Student::where('classroom_id', $schedule->classroom_id)
            ->orderBy('nama', 'asc')
            ->get();

        return view('absensi.absen', compact('schedule', 'students'));
    }



    public function store(AttendanceRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {

            $activeYearId = 1;

            $attendance = Attendance::updateOrCreate(
                [
                    'schedule_id' => $validated['schedule_id'],
                    'tanggal'     => $validated['tanggal']
                ],
                [
                    'academic_year_id' => $activeYearId,
                    'updated_at'  => now()
                ]
            );

            foreach ($validated['absensi'] as $item) {
                $attendance->details()->updateOrCreate(
                    ['student_id' => $item['student_id']],
                    ['status'     => ucfirst($item['status'])]
                );
            }
        });
        return redirect()->route('guru.dashboard')->with('success', 'Absensi berhasil disimpan');
    }



    // ===============================
    // DETAIL
    // ===============================
    public function show(Attendance $attendance)
    {
        return response()->json([
            'status' => true,
            'data' => $attendance->load([
                'schedule.teacher',
                'schedule.subject',
                'details.student'
            ])
        ]);
    }


    // ===============================
    // UPDATE
    // ===============================
    public function update(AttendanceRequest $request, Attendance $attendance)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $attendance) {

            // Update master
            $attendance->update([
                'schedule_id' => $validated->schedule_id,
                'tanggal' => $validated->tanggal,
            ]);

            // Refresh detail
            $attendance->details()->delete();

            foreach ($validated->absensi as $item) {

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


    // ===============================
    // DELETE
    // ===============================
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data absensi berhasil dihapus'
        ], 200);
    }
}
