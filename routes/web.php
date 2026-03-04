<?php

use App\Http\Controllers\{
    AdminController,
    AttendanceController,
    AuthController,
    EvaluationController,
    StudentController,
    TeacherController
};
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', fn() => view('layouts.app'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Modul Guru
    Route::prefix('guru')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'index'])->name('guru.dashboard');
        Route::get('/guru/siswa/{id}', [TeacherController::class, 'showStudent'])->name('guru.siswa.detail');
        Route::get('/guru/kelas', [TeacherController::class, 'listClasses'])->name('guru.kelas.index');
        Route::get('/guru/kelas/{id}', [TeacherController::class, 'showClassroom'])->name('guru.kelas.show');

        // Route Absensi
        Route::get('/absensi', [TeacherController::class, 'absensiIndex'])->name('guru.absensi');
        Route::get('/absensi/input/{schedule_id}', [TeacherController::class, 'createAbsensi'])->name('guru.absensi.create');
        Route::post('/absensi/store', [AttendanceController::class, 'store'])->name('guru.absensi.store');

        // Route Penilaian
        Route::resource('evaluation', EvaluationController::class);
        Route::get('/guru/penilaian', [TeacherController::class, 'penilaianIndex'])->name('guru.penilaian.index');
        Route::get('/guru/evaluation/create/{schedule_id}', [EvaluationController::class, 'create'])
            ->name('evaluations.create');
        Route::post('/guru/nilai/store', [EvaluationController::class, 'store'])->name('evaluation.store');
        Route::delete('/evaluation-detail/{id}', [EvaluationController::class, 'destroyDetailNilai'])->name('evaluation.detail.destroy');
    });

    // Modul Admin & Data Master
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/data-siswa', [StudentController::class, 'indexBlade'])->name('students.data');
});
