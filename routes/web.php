<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('home');
});

Route::get('absen', function () {
    return view('Guru.absen');
});

Route::get('nilai', function () {
    return view('Guru.nilai');
});

Route::get('data', function () {
    return view('Siswa.data');
});

Route::get('login', function () {
    return view('Auth.login');
});

Route::get('/dashboard', [AdminController::class, 'index']);