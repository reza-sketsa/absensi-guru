<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dashboard
        $totalGuru  = 0;
        $hadir      = 0;
        $terlambat  = 0;
        $alpha      = 0;

        $absensi = collect([]);

        // Data siswa buat home
        $students = Student::orderBy('nama','asc')->get();

        return view('home', compact(
            'totalGuru',
            'hadir',
            'terlambat',
            'alpha',
            'absensi',
            'students'
        ));
    }
}