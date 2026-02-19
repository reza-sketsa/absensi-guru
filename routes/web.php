<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;

// AUTH
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// HOME
Route::get('/', fn() => view('welcome'));

// DASHBOARD
Route::get('/dashboard', [AdminController::class, 'index'])->middleware('auth');

// ABSEN & ABSENSI
Route::get('/absen', fn() => view('absen'));
Route::get('/absensi', fn() => view('absensi.absen'));

// DATA / NILAI
Route::get('/nilai', fn() => view('nilai'));
Route::get('/data', [App\Http\Controllers\StudentController::class, 'indexBlade']);

Route::get('/nilai/input/{student}', [StudentController::class, 'input'])->name('nilai.input');
Route::post('/nilai/store', [App\Http\Controllers\StudentController::class, 'storeNilai'])->name('nilai.store');


// STUDENTS (opsional)
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
