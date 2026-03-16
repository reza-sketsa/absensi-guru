    <?php

    use App\Http\Controllers\Admin\AdminController as DashboardAdmin;
    use App\Http\Controllers\Admin\StudentController;
    use App\Http\Controllers\AttendanceController;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\ClassroomController; // Controller Guru di folder utama
    use App\Http\Controllers\EvaluationController;
    use App\Http\Controllers\TeacherController as DashboardGuru;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;

    // Public Routes
    Route::get('/', function () {
        if (Auth::check()) {
            if (strtolower(Auth::user()->role) === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('guru.dashboard');
        }
        return view('layouts.app'); // Atau arahkan ke view landing page kamu
    });
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected Routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // ==========================================
        // MODUL GURU (Halaman Operasional Guru)
        // ==========================================
        Route::prefix('guru')->name('guru.')->group(function () {
            Route::get('/dashboard', [DashboardGuru::class, 'index'])->name('dashboard');
            Route::get('/siswa/{id}', [DashboardGuru::class, 'showStudent'])->name('siswa.detail');
            Route::get('/kelas', [DashboardGuru::class, 'listClasses'])->name('kelas.index');
            Route::get('/kelas/{id}', [DashboardGuru::class, 'showClassroom'])->name('kelas.show');

            // Route Absensi
            Route::get('/absensi', [DashboardGuru::class, 'absensiIndex'])->name('absensi');
            Route::get('/absensi/input/{schedule_id}', [DashboardGuru::class, 'createAbsensi'])->name('absensi.create');
            Route::post('/absensi/store', [AttendanceController::class, 'store'])->name('absensi.store');

            // Route Penilaian (Custom Actions Harus di Atas Resource)
            Route::get('/evaluations/trash', [EvaluationController::class, 'trash'])->name('evaluations.trash');
            Route::post('/evaluations/{id}/restore', [EvaluationController::class, 'restore'])->name('evaluations.restore');
            Route::post('/evaluations/restore-detail/{id}', [EvaluationController::class, 'restoreDetailNilai'])->name('evaluations.detail.restore');
            Route::delete('/evaluations/detail/{id}', [EvaluationController::class, 'destroyDetailNilai'])->name('evaluations.detail.destroy');
            Route::get('/evaluations/create/{schedule_id}', [EvaluationController::class, 'create'])->name('evaluations.create');

            // Resource Standar (index, create, store, show, edit, update, destroy)
            Route::resource('evaluations', EvaluationController::class);

            Route::get('/penilaian', [DashboardGuru::class, 'penilaianIndex'])->name('penilaian.index');
        });

        // ==========================================
        // MODUL ADMIN (Halaman Manajemen Data Master)
        // ==========================================
        Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
            // Dashboard Admin
            Route::get('/dashboard', [DashboardAdmin::class, 'index'])->name('dashboard');

            // CRUD Data Master (Buka komentar ini HANYA JIKA filenya sudah ada di folder Admin)
            Route::resource('guru', \App\Http\Controllers\Admin\TeacherController::class);
            Route::resource('kelas', \App\Http\Controllers\Admin\ClassroomController::class);
            Route::get('/kelas/{kelas_id}/students', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('kelas.students.index');
            Route::post('/kelas/{kelas_id}/students', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('kelas.students.store');
            Route::post('kelas/{kelas_id}/students/import', [\App\Http\Controllers\Admin\StudentController::class, 'import'])
                ->name('students.import');
            // Route::resource('jadwal', \App\Http\Controllers\Admin\ScheduleController::class);
        });
    });
