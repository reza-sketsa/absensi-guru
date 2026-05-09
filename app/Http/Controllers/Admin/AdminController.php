<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'today'); // Default ke hari ini
        $query = DB::table('attendances');

        // Logika Filter Tanggal
        if ($filter == 'weekly') {
            $startDate = now()->startOfWeek()->toDateString();
            $endDate = now()->endOfWeek()->toDateString();
        } elseif ($filter == 'monthly') {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        } elseif ($filter == 'semester') {
            // Asumsi Semester Genap (Jan-Jun) atau Ganjil (Jul-Des)
            if (now()->month >= 7) {
                $startDate = now()->year . '-07-01';
                $endDate = now()->year . '-12-31';
            } else {
                $startDate = now()->year . '-01-01';
                $endDate = now()->year . '-06-30';
            }
        } else {
            $startDate = now()->toDateString();
            $endDate = now()->toDateString();
        }

        // 1. Statistik Total berdasarkan rentang tanggal
        $stats = DB::table('attendance_details')
            ->join('attendances', 'attendances.id', '=', 'attendance_details.attendance_id')
            ->whereBetween('attendances.tanggal', [$startDate, $endDate])
            ->selectRaw("
            SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) as izin,
            SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) as sakit,
            SUM(CASE WHEN status = 'Alpa' THEN 1 ELSE 0 END) as alpa
        ")->first();

        // 2. Rekap Per Kelas berdasarkan rentang tanggal
        $rekapKelas = DB::table('classrooms')
            ->leftJoin('students', 'students.classroom_id', '=', 'classrooms.id')
            ->leftJoin('attendance_details', 'attendance_details.student_id', '=', 'students.id')
            ->leftJoin('attendances', function ($join) use ($startDate, $endDate) {
                $join->on('attendances.id', '=', 'attendance_details.attendance_id')
                    ->whereBetween('attendances.tanggal', [$startDate, $endDate]);
            })
            ->selectRaw("CONCAT(classrooms.tingkat, '-', classrooms.paralel) as nama_kelas")
            ->selectRaw("
            SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
            SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) as izin,
            SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) as sakit,
            SUM(CASE WHEN status = 'Alpa' THEN 1 ELSE 0 END) as alpa
        ")
            ->groupBy('classrooms.id', 'classrooms.tingkat', 'classrooms.paralel')
            ->orderBy('classrooms.tingkat', 'asc')
            ->orderBy('classrooms.paralel', 'asc')
            ->get();

        // Tambah di bawah query $rekapKelas
        $today = now()->toDateString();
        $daftarHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hariIni = $daftarHari[now()->dayOfWeek];

        $jadwalBelumAbsen = DB::table('schedules')
            ->join('teachers', 'teachers.id', '=', 'schedules.teacher_id')
            ->join('subjects', 'subjects.id', '=', 'schedules.subject_id')
            ->join('classrooms', 'classrooms.id', '=', 'schedules.classroom_id')
            ->leftJoin('attendances', function ($join) use ($today) {
                $join->on('attendances.schedule_id', '=', 'schedules.id')
                    ->where('attendances.tanggal', $today);
            })
            ->whereNull('attendances.id')
            ->where('schedules.hari', $hariIni)
            ->select(
                'schedules.id',
                'teachers.nama_guru',
                'subjects.nama_mapel',
                'classrooms.tingkat',
                'classrooms.paralel',
                'schedules.jam_mulai',
                'schedules.jam_habis'
            )
            ->orderBy('schedules.jam_mulai')
            ->get();

        return view('admin.dashboard', [
            'hadir'              => $stats->hadir ?? 0,
            'izin'               => $stats->izin  ?? 0,
            'sakit'              => $stats->sakit ?? 0,
            'alpa'               => $stats->alpa  ?? 0,
            'rekapKelas'         => $rekapKelas,
            'filter'             => $filter,
            'jadwalBelumAbsen'   => $jadwalBelumAbsen,
        ]);
    }
}
