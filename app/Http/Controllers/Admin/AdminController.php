<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {

        $filter = $request->get('filter', 'weekly');
        $selectedYearId = $request->get('academic_year_id');

        $allYears = AcademicYear::orderBy('id', 'desc')->get();
        $activeYear = $selectedYearId
            ? AcademicYear::find($selectedYearId)
            : AcademicYear::where('is_active', true)->first();

        if ($filter == 'weekly') {
            $startDate = now()->startOfWeek()->toDateString();
            $endDate   = now()->endOfWeek()->toDateString();
        } elseif ($filter == 'monthly') {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate   = now()->endOfMonth()->toDateString();
        } elseif ($filter == 'semester') {
            if (now()->month >= 7) {
                $startDate = now()->year . '-07-01';
                $endDate   = now()->year . '-12-31';
            } else {
                $startDate = now()->year . '-01-01';
                $endDate   = now()->year . '-06-30';
            }
        } else {
            $startDate = now()->toDateString();
            $endDate   = now()->toDateString();
        }

        $today = now()->toDateString();
        $daftarHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hariIni = $daftarHari[now()->dayOfWeek];

        $keaktifanGuru = DB::table('teachers')
            ->leftJoin('schedules', function ($join) use ($activeYear) {
                $join->on('schedules.teacher_id', '=', 'teachers.id');
                if ($activeYear) {
                    $join->where('schedules.academic_year_id', $activeYear->id);
                }
            })
            ->leftJoin('attendances', function ($join) use ($startDate, $endDate, $activeYear) {
                $join->on('attendances.schedule_id', '=', 'schedules.id')
                    ->whereBetween('attendances.tanggal', [$startDate, $endDate]);
                if ($activeYear) {
                    $join->where('attendances.academic_year_id', $activeYear->id);
                }
            })
            ->selectRaw("
        teachers.id,
        teachers.nama_guru,
        COUNT(DISTINCT attendances.id) as total_absen,
        COUNT(DISTINCT schedules.id) as total_jadwal
    ")
            ->groupBy('teachers.id', 'teachers.nama_guru')
            ->orderByDesc('total_absen')
            ->get()
            ->map(function ($item) use ($startDate, $endDate) {
                // Hitung berapa minggu dalam periode filter
                $start  = \Carbon\Carbon::parse($startDate);
                $end    = \Carbon\Carbon::parse($endDate);
                $minggu = max(1, $start->diffInWeeks($end) + 1);

                // Total pertemuan yang seharusnya = jumlah jadwal × jumlah minggu
                $expectedPertemuan = $item->total_jadwal * $minggu;

                $item->persentase = $expectedPertemuan > 0
                    ? min(100, round(($item->total_absen / $expectedPertemuan) * 100))
                    : 0;

                return $item;
            });

        $totalGuruAktif = $keaktifanGuru->where('total_absen', '>', 0)->count();
        $totalGuru      = $keaktifanGuru->count();

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
            ->when($activeYear, fn($q) => $q->where('schedules.academic_year_id', $activeYear->id)) // tambah
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

        $trendHarian = DB::table('attendances')
            ->join('schedules', 'schedules.id', '=', 'attendances.schedule_id')
            ->whereBetween('attendances.tanggal', [$startDate, $endDate])
            ->when($activeYear, fn($q) => $q->where('attendances.academic_year_id', $activeYear->id)) // tambah
            ->selectRaw("
            DATE(attendances.tanggal) as tanggal,
            COUNT(DISTINCT schedules.teacher_id) as total_guru
        ")
            ->groupBy('attendances.tanggal')
            ->orderBy('attendances.tanggal')
            ->get();


        $chartLabels = $trendHarian->pluck('tanggal')->map(fn($t) => \Carbon\Carbon::parse($t)->translatedFormat('d M'))->values();
        $chartData   = $trendHarian->pluck('total_guru')->values();

        return view('admin.dashboard', compact(
            'filter',
            'keaktifanGuru',
            'totalGuruAktif',
            'totalGuru',
            'jadwalBelumAbsen',
            'chartLabels',
            'chartData',
            'allYears',
            'activeYear',
        ));
    }
}
