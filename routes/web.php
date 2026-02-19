<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');


Route::get('/', fn() => view('welcome'));


Route::get('/dashboard', [AdminController::class, 'index'])->middleware('auth');


Route::get('/absen', fn() => view('absen'));

Route::get('/absensi', [AttendanceController::class, 'index']);
Route::post('/absensi/store', [AttendanceController::class, 'store']);
Route::get('/absensi-api', [AttendanceController::class, 'apiIndex']);


// halaman data siswa
Route::get('/data', [StudentController::class, 'indexBlade'])->name('students.data');

// input nilai
Route::get('/nilai/input/{student}', [StudentController::class, 'input'])
    ->name('nilai.input');

Route::post('/nilai/store', [StudentController::class, 'storeNilai'])
    ->name('nilai.store');

// optional API / admin list
Route::get('/students', [StudentController::class, 'index'])
    ->name('students.index');
