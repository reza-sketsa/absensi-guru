<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\DashboardController as DashboardGuru;
use App\Http\Controllers\Admin\AdminController as DashboardAdmin;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\AcademicYearController;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', function () {
    if (Auth::check()) {
        return strtolower(Auth::user()->role) === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('guru.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ==========================================
// PROTECTED ROUTES (AUTH)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ------------------------------------------
    // MODUL GURU
    // ------------------------------------------
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [DashboardGuru::class, 'guruDashboard'])->name('dashboard');
        Route::get('/siswa/{id}', [AttendanceController::class, 'showStudent'])->name('siswa.detail');
        Route::get('/kelas', [DashboardGuru::class, 'listClasses'])->name('kelas.index');
        Route::get('/kelas/{id}', [DashboardGuru::class, 'showClassroom'])->name('kelas.show');
        Route::get('/rekap/{classroom_id}', [DashboardGuru::class, 'rekapKelas'])->name('rekap.kelas');

        // Absensi
        Route::get('/absensi', [AttendanceController::class, 'absensiIndex'])->name('absensi');
        Route::get('/absensi/input/{schedule_id}', [AttendanceController::class, 'createAbsensi'])->name('absensi.create');
        Route::post('/absensi/store', [AttendanceController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/{schedule_id}/edit', [AttendanceController::class, 'editAbsensi'])->name('absensi.edit');
        Route::post('/absensi/{schedule_id}/update', [AttendanceController::class, 'updateAbsensi'])->name('absensi.update');
        Route::get('/absensi/{schedule_id}/history', [AttendanceController::class, 'historyAbsensi'])->name('absensi.history');
        Route::get('/absensi/{schedule_id}/history/{attendance_id}', [AttendanceController::class, 'historyDetail'])->name('absensi.history.detail');


        // Penilaian
        Route::get('/penilaian', [EvaluationController::class, 'index'])->name('penilaian.index');
        Route::get('/evaluations/trash', [EvaluationController::class, 'trash'])->name('evaluations.trash');
        Route::post('/evaluations/{id}/restore', [EvaluationController::class, 'restore'])->name('evaluations.restore');
        Route::delete('/evaluations/{id}/force-delete', [EvaluationController::class, 'forceDeleteEvaluation'])->name('evaluations.force-delete');

        // 1. Rute kustom untuk create dengan parameter schedule_id tetap di sini
        Route::get('/evaluations/create/{schedule_id}', [EvaluationController::class, 'create'])->name('evaluations.create');

        // 2. Tambahkan ->except(['create']) pada rute resource agar tidak membuat duplikat nama
        Route::resource('evaluations', EvaluationController::class)->except(['create']);
    });

    // ------------------------------------------
    // MODUL ADMIN (Dikelompokkan Jadi Satu)
    // ------------------------------------------
    Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardAdmin::class, 'index'])->name('dashboard');

        // Data Master
        Route::resource('guru', TeacherController::class);
        Route::resource('kelas', ClassroomController::class);
        Route::resource('mapel', SubjectController::class);
        Route::resource('jadwal', ScheduleController::class);

        // Manajemen Siswa per Kelas
        Route::get('/kelas/{kelas_id}/students', [StudentController::class, 'index'])->name('kelas.students.index');
        Route::post('/kelas/{kelas_id}/students', [StudentController::class, 'store'])->name('kelas.students.store');
        Route::post('/kelas/{kelas_id}/students/import', [StudentController::class, 'import'])->name('students.import');

        //kelas
        Route::put('/kelas/{kelas_id}/students/{id}', [StudentController::class, 'update'])->name('kelas.students.update');
        Route::delete('/kelas/{kelas_id}/students/{id}', [StudentController::class, 'destroy'])->name('kelas.students.destroy');

        // Setting Tahun Akademik (Fixing Route Name mismatch)
        Route::get('/academic-year', [AcademicYearController::class, 'index'])->name('tahun-ajaran.index');
        Route::post('/academic-year', [AcademicYearController::class, 'store'])->name('tahun-ajaran.store');
        Route::post('/academic-year/{id}/activate', [AcademicYearController::class, 'activate'])->name('tahun-ajaran.activate');
    });
});

use Illuminate\Support\Facades\Artisan;

Route::get('/gas-seeder', function () {
    try {
        // Memanggil DatabaseSeeder bawaan yang sudah kamu buat
        Artisan::call('db:seed');
        return 'Mantap! Semua data dummy dan akun admin berhasil dimasukkan ke database Railway.';
    } catch (\Exception $e) {
        return 'Waduh, gagal jalan karena: ' . $e->getMessage();
    }
});
