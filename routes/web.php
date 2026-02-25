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

    // =====================
    // ABSENSI
    // =====================

    Route::get('/absensi', [AttendanceController::class, 'index'])
        ->name('absensi.index');

    Route::post('/absensi/store', [AttendanceController::class, 'store'])
        ->name('absensi.store');

    Route::get('/absensi-api', [AttendanceController::class, 'apiIndex']);

    // =====================
    // DATA SISWA
    // =====================

    Route::get('/data', [StudentController::class, 'indexBlade'])
        ->name('students.data');

    // Nilai
    Route::get('/nilai/input/{student}', [EvaluationController::class, 'create'])
        ->name('evaluation.create');

    Route::post('/nilai/store', [EvaluationController::class, 'store'])
        ->name('evaluation.store');

    // Jika di JS Anda memanggil /evaluation-detail/{id}
    Route::delete('/evaluation-detail/{idNilai}', [EvaluationController::class, 'destroyDetailNilai'])
        ->name('evaluation.detail.destroy');

    // optional API student list
    Route::get('/students', [StudentController::class, 'index'])
        ->name('students.index');
});

//Recycle Bin
Route::prefix('trash')->group(function () {
    Route::get('/evaluations', [EvaluationController::class, 'trash'])->name('trash.index');
    Route::put('/evaluations/{id}/restore', [EvaluationController::class, 'restore'])->name('trash.restore');
    Route::delete('/evaluations/{id}/force-delete', [EvaluationController::class, 'forceDelete'])->name('trash.force-delete');
});
