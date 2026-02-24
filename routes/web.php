<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [AdminController::class, 'index']);

    // absensi
    Route::get('/absen', fn() => view('absen'));
    Route::get('/absensi', [AttendanceController::class, 'index']);
    Route::post('/absensi/store', [AttendanceController::class, 'store']);
    Route::get('/absensi-api', [AttendanceController::class, 'apiIndex']);

    // halaman data siswa (blade)
    Route::get('/data', [StudentController::class, 'indexBlade'])
        ->name('students.data');

    // input nilai
    Route::get('/nilai/input/{student}', [EvaluationController::class, 'create'])
        ->name('evaluation.create');

    Route::post('/nilai/store', [EvaluationController::class, 'store'])
        ->name('evaluation.store');

    // optional API student list
    Route::get('/students', [StudentController::class, 'index'])
        ->name('students.index');
});
