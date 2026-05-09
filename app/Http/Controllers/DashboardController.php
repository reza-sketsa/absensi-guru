<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDetail;
use App\Models\Classroom;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

class DashboardController extends Controller
{
    private function getTeacherId()
    {
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Silahkan login kembali.');
        }

        $teacher = $user->teacher ?: Teacher::where('user_id', $user->id)->first();

        return $teacher ? $teacher->id : abort(403, 'User tidak terhubung ke data Guru.');
    }

    private function getDateRange(string $filter): array
    {
        return match ($filter) {
            'weekly'   => [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()],
            'monthly'  => [Carbon::now()->startOfMonth()->toDateString(), Carbon::now()->endOfMonth()->toDateString()],
            'semester' => $this->getSemesterRange(),
            default    => [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()],
        };
    }

    private function getSemesterRange(): array
    {
        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        return $month >= 7
            ? [$year . '-07-01', $year . '-12-31']
            : [$year . '-01-01', $year . '-06-30'];
    }

    public function guruDashboard(Request $request)
    {
        $teacherId = $this->getTeacherId();
        $filter    = $request->get('filter', 'weekly');

        [$startDate, $endDate] = $this->getDateRange($filter);

        $query = AttendanceDetail::whereHas('attendance.schedule', fn($q) => $q->where('teacher_id', $teacherId))
            ->whereHas('attendance', fn($q) => $q->whereBetween('tanggal', [$startDate, $endDate]));

        $rawStats = $query->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $stats = [
            'hadir' => $rawStats['Hadir'] ?? 0,
            'izin' => $rawStats['Izin'] ?? 0,
            'sakit' => $rawStats['Sakit'] ?? 0,
            'Alpa' => $rawStats['Alpa'] ?? 0,
        ];

        $lowAttendanceStudents = AttendanceDetail::whereHas('attendance.schedule', fn($q) => $q->where('teacher_id', $teacherId))
            ->whereIn('status', ['Alpa', 'Sakit', 'Izin'])
            ->whereHas('attendance', fn($q) => $q->whereBetween('tanggal', [$startDate, $endDate]))
            ->select(
                'student_id',
                DB::raw('count(*) as total_tidak_hadir'),
                DB::raw("SUM(CASE WHEN status = 'Alpa' THEN 1 ELSE 0 END) as total_alpa")
            )
            ->with('student:id,nama,classroom_id')
            ->with('student.classroom')
            ->groupBy('student_id')
            ->orderByDesc('total_alpa')
            ->take(5)
            ->get();

        $rekapKelas = DB::table('classrooms')
            ->join('schedules', 'schedules.classroom_id', '=', 'classrooms.id')
            ->join('attendances', 'attendances.schedule_id', '=', 'schedules.id')
            ->join('attendance_details', 'attendance_details.attendance_id', '=', 'attendances.id')
            ->where('schedules.teacher_id', $teacherId)
            ->whereBetween('attendances.tanggal', [$startDate, $endDate])
            ->selectRaw("
            classrooms.id as classroom_id,
            CONCAT(classrooms.tingkat, '-', classrooms.paralel) as nama_kelas,
            SUM(CASE WHEN attendance_details.status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN attendance_details.status = 'Izin' THEN 1 ELSE 0 END) as izin,
            SUM(CASE WHEN attendance_details.status = 'Sakit' THEN 1 ELSE 0 END) as sakit,
            SUM(CASE WHEN attendance_details.status = 'Alpa' THEN 1 ELSE 0 END) as alpa
        ")
            ->groupBy('classrooms.id', 'classrooms.tingkat', 'classrooms.paralel')
            ->get();

        return view('guru.dashboard', compact('stats', 'filter', 'lowAttendanceStudents', 'rekapKelas'));
    }

    public function rekapKelas(Request $request, $classroom_id)
    {
        $teacherId = $this->getTeacherId();
        $filter    = $request->get('filter', 'weekly');

        [$startDate, $endDate] = $this->getDateRange($filter);

        $classroom = \App\Models\Classroom::findOrFail($classroom_id);

        $rekapSiswa = DB::table('students')
            ->leftJoin('attendance_details', 'attendance_details.student_id', '=', 'students.id')
            ->leftJoin('attendances', function ($join) use ($startDate, $endDate) {
                $join->on('attendances.id', '=', 'attendance_details.attendance_id')
                    ->whereBetween('attendances.tanggal', [$startDate, $endDate]);
            })
            ->leftJoin('schedules', function ($join) use ($teacherId) {
                $join->on('schedules.id', '=', 'attendances.schedule_id')
                    ->where('schedules.teacher_id', $teacherId);
            })
            ->where('students.classroom_id', $classroom_id)
            ->selectRaw("
            students.id,
            students.nama,
            students.nis,
            SUM(CASE WHEN attendance_details.status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN attendance_details.status = 'Izin' THEN 1 ELSE 0 END) as izin,
            SUM(CASE WHEN attendance_details.status = 'Sakit' THEN 1 ELSE 0 END) as sakit,
            SUM(CASE WHEN attendance_details.status = 'Alpa' THEN 1 ELSE 0 END) as alpa
        ")
            ->groupBy('students.id', 'students.nama', 'students.nis')
            ->orderBy('students.nama')
            ->get();

        return view('guru.rekap.kelas', compact('classroom', 'rekapSiswa', 'filter'));
    }

    public function listClasses()
    {
        $teacherId = $this->getTeacherId();

        $classrooms = Classroom::where('walas_id', $teacherId)
            ->orWhereHas('schedules', fn($q) => $q->where('teacher_id', $teacherId))
            ->withCount('students')
            ->get();

        return view('guru.kelas.index', compact('classrooms'));
    }

    public function showClassroom($id)
    {
        $classroom = Classroom::with(['students' => fn($q) => $q->orderBy('nama', 'asc')])->findOrFail($id);

        return view('guru.kelas.show', compact('classroom'));
    }

    public function penilaianIndex()
    {
        $teacherId = $this->getTeacherId();

        $schedules = Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', $teacherId)
            ->get();

        return view('guru.nilai.nilai', compact('schedules'));
    }
}
