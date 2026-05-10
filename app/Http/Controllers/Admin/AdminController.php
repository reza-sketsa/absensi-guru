<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'weekly');

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

        $keaktifanGuru = DB::table('teachers')
            ->leftJoin('schedules', 'schedules.teacher_id', '=', 'teachers.id')
            ->leftJoin('attendances', function ($join) use ($startDate, $endDate) {
                $join->on('attendances.schedule_id', '=', 'schedules.id')
                    ->whereBetween('attendances.tanggal', [$startDate, $endDate]);
            })
            ->selectRaw("
            teachers.id,
            teachers.nama_guru,
            COUNT(DISTINCT schedules.id) as total_jadwal,
            COUNT(DISTINCT attendances.id) as total_absen
        ")
            ->groupBy('teachers.id', 'teachers.nama_guru')
            ->orderByDesc('total_absen')
            ->get()
            ->map(function ($item) {
                $item->persentase = $item->total_jadwal > 0
                    ? round(($item->total_absen / $item->total_jadwal) * 100)
                    : 0;
                return $item;
            });

        $totalGuruAktif = $keaktifanGuru->where('total_absen', '>', 0)->count();
        $totalGuru      = $keaktifanGuru->count();

        $today    = now()->toDateString();
        $daftarHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hariIni  = $daftarHari[now()->dayOfWeek];

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

        // Chart Harian
        $trendHarian = DB::table('attendances')
            ->join('schedules', 'schedules.id', '=', 'attendances.schedule_id')
            ->whereBetween('attendances.tanggal', [$startDate, $endDate])
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
            'chartData'
        ));
    }
}
