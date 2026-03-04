<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    // Helper privat untuk ambil Teacher ID dari User yang login
    private function getTeacherId()
    {
        $user = Auth::user();
        $teacher = $user->teacher ?: Teacher::where('user_id', $user->id)->first();

        return $teacher ? $teacher->id : abort(403, 'User tidak terhubung ke data Guru.');
    }

    public function index(Request $request)
    {
        $teacherId = $this->getTeacherId();
        $filter = request('filter', 'all'); // Default tampilkan semua jika tidak ada filter

        $query = \App\Models\AttendanceDetail::whereHas('attendance.schedule', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        });

        // Logika Filter Rentang Waktu
        if ($filter == 'weekly') {
            $query->whereHas(
                'attendance',
                fn($q) =>
                $q->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            );
        } elseif ($filter == 'monthly') {
            $query->whereHas(
                'attendance',
                fn($q) =>
                $q->whereMonth('tanggal', Carbon::now()->month)
                    ->whereYear('tanggal', Carbon::now()->year)
            );
        } elseif ($filter == 'semester') {
            // Asumsi Semester Ganjil: Juli-Desember, Genap: Januari-Juni
            $month = Carbon::now()->month;
            $startMonth = ($month >= 7) ? 7 : 1;
            $endMonth = ($month >= 7) ? 12 : 6;

            $query->whereHas(
                'attendance',
                fn($q) =>
                $q->whereMonth('tanggal', '>=', $startMonth)
                    ->whereMonth('tanggal', '<=', $endMonth)
                    ->whereYear('tanggal', Carbon::now()->year)
            );
        }

        $rawStats = $query->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $stats = [
            'hadir' => $rawStats['Hadir'] ?? 0,
            'izin'  => $rawStats['Izin']  ?? 0,
            'sakit' => $rawStats['Sakit'] ?? 0,
            'alpa'  => $rawStats['Alpa']  ?? 0,
        ];

        $lowAttendanceStudents = \App\Models\AttendanceDetail::whereHas('attendance.schedule', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })
            ->whereIn('status', ['Alpa', 'Sakit', 'Izin']) // Fokus pada ketidakhadiran
            // Gunakan filter yang sama dengan Chart
            ->when($filter == 'weekly', fn($q) => $q->whereHas('attendance', fn($a) => $a->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])))
            ->when($filter == 'monthly', fn($q) => $q->whereHas('attendance', fn($a) => $a->whereMonth('tanggal', Carbon::now()->month)->whereYear('tanggal', Carbon::now()->year)))
            ->when($filter == 'semester', function ($q) {
                $month = Carbon::now()->month;
                $start = ($month >= 7) ? 7 : 1;
                $end = ($month >= 7) ? 12 : 6;
                $q->whereHas('attendance', fn($a) => $a->whereMonth('tanggal', '>=', $start)->whereMonth('tanggal', '<=', $end));
            })
            ->select(
                'student_id',
                DB::raw('count(*) as total_tidak_hadir'),
                DB::raw("SUM(CASE WHEN status = 'Alpa' THEN 1 ELSE 0 END) as total_alpa")
            )
            ->with('student:id,nama')
            ->groupBy('student_id')
            ->orderByDesc('total_alpa')
            ->take(5)
            ->get();

        return view('guru.dashboard', compact('stats', 'filter', 'lowAttendanceStudents'));
    }

    public function showStudent($id)
    {
        $student = \App\Models\Student::with('classroom')->findOrFail($id);

        // Ambil semua riwayat absensi siswa ini yang diajar oleh guru yang sedang login
        $teacherId = $this->getTeacherId();

        $attendanceHistory = \App\Models\AttendanceDetail::where('student_id', $id)
            ->whereHas('attendance.schedule', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->with('attendance')
            ->orderByDesc(function ($query) {
                $query->select('tanggal')
                    ->from('attendances')
                    ->whereColumn('attendances.id', 'attendance_details.attendance_id')
                    ->limit(1);
            })
            ->get();

        // Hitung ringkasan status
        $summary = [
            'Hadir' => $attendanceHistory->where('status', 'Hadir')->count(),
            'Sakit' => $attendanceHistory->where('status', 'Sakit')->count(),
            'Izin'  => $attendanceHistory->where('status', 'Izin')->count(),
            'Alpa'  => $attendanceHistory->where('status', 'Alpa')->count(),
        ];

        return view('guru.absensi.student-detail', compact('student', 'attendanceHistory', 'summary'));
    }

    public function listClasses()
    {
        $teacherId = $this->getTeacherId();

        // Ambil kelas di mana guru ini mengajar atau menjadi wali kelas
        $classrooms = \App\Models\Classroom::where('walas_id', $teacherId)
            ->orWhereHas('schedules', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->withCount('students')
            ->get();

        return view('guru.kelas.index', compact('classrooms'));
    }

    public function showClassroom($id)
    {
        $classroom = \App\Models\Classroom::with(['students' => function ($q) {
            $q->orderBy('nama', 'asc');
        }])->findOrFail($id);

        return view('guru.kelas.show', compact('classroom'));
    }

    public function absensiIndex()
    {
        $teacherId = $this->getTeacherId();

        $schedules = Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', $teacherId)
            ->get();

        $recent_attendances = Attendance::with(['schedule.subject', 'schedule.classroom'])
            ->withCount([
                'details as h' => fn($q) => $q->where('status', 'Hadir'),
                'details as i' => fn($q) => $q->where('status', 'Izin'),
                'details as s' => fn($q) => $q->where('status', 'Sakit'),
                'details as a' => fn($q) => $q->where('status', 'Alpa'),
            ])
            ->whereHas('schedule', fn($q) => $q->where('teacher_id', $teacherId))
            ->latest('tanggal')
            ->take(5)
            ->get();

        return view('guru.absensi.absen', compact('schedules', 'recent_attendances'));
    }

    public function penilaianIndex()
    {
        $teacherId = $this->getTeacherId();

        $schedules = \App\Models\Schedule::with(['subject', 'classroom'])
            ->where('teacher_id', $teacherId)
            ->get();

        return view('guru.nilai.nilai', compact('schedules'));
    }

    // PROSES: Tampilkan Form Absen (Daftar Siswa)
    public function createAbsensi($schedule_id)
    {
        $schedule = Schedule::with(['classroom', 'subject'])->findOrFail($schedule_id);
        $students = Student::where('classroom_id', $schedule->classroom_id)
            ->orderBy('nama', 'asc')
            ->get();

        return view('guru.absensi.input-absen', compact('schedule', 'students'));
    }
}
