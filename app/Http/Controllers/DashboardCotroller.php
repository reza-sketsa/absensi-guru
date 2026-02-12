<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data sementara (biar tidak error)
        $totalGuru  = 0;
        $hadir      = 0;
        $terlambat  = 0;
        $alpha      = 0;

        // Data tabel kosong dulu
        $absensi = collect([]);

        return view('Admin.dashboard', compact(
            'totalGuru',
            'hadir',
            'terlambat',
            'alpha',
            'absensi'
        ));
    }
}
