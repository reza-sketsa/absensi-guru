<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/absensi', function () {
    return view('absensi.absen');
});

Route::get('/nilai', function () {
    return view('nilai.nilai');
});

Route::get('/data', function () {
    return view('siswa.data');
});

Route::get('/dashboard', [AdminController::class, 'index'])->middleware('auth');

Route::get('students', [StudentController::class, 'index']) ->name('students.index');
