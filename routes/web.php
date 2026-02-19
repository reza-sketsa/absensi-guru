<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;


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

// PAKAI CONTROLLER (BUKAN VIEW LANGSUNG)
Route::get('/absensi', [AttendanceController::class,'index']);
Route::post('/absensi/store', [AttendanceController::class,'store']);
Route::get('/absensi-api', [AttendanceController::class,'apiIndex']);


// DATA / NILAI
Route::get('/nilai', fn() => view('nilai'));
<<<<<<< HEAD
Route::get('/data', [App\Http\Controllers\StudentController::class, 'indexBlade']);

Route::get('/nilai/input/{student}', [StudentController::class, 'input'])->name('nilai.input');
Route::post('/nilai/store', [App\Http\Controllers\StudentController::class, 'storeNilai'])->name('nilai.store');


// STUDENTS (opsional)
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
=======
Route::get('/data', [StudentController::class, 'index']);

// STUDENTS
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
>>>>>>> f250fa9877bfdcb9267e9fd5713bee729fad2e17
