<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $school = \App\Models\School::create([
            'nama_sekolah' => 'SMPN 1 KOTABARU',
            'alamat' => 'Jl. Sampel No. 1',
            'no_telp' => '081234567890',
            'email' => 'smpn1ktb@gmail.com'
        ]);

        // 1. Buat Tahun Ajaran Aktif
        $year = \App\Models\AcademicYear::create([
            'tahun' => '2025/2026',
            'semester' => 'Ganjil',
            'is_active' => true
        ]);

        // 2. Buat User untuk Login Guru
        $userTeacher = \App\Models\User::create([
            'username' => 'budi',
            'password' => bcrypt('123'),
            'role' => 'Guru',
        ]);

        // 3. Buat Data GURU (Lengkap dengan alamat & user_id)
        $teacher = \App\Models\Teacher::create([
            'user_id' => $userTeacher->id,
            'nama_guru' => 'Budi Santoso, S.Pd',
            'nip' => '198001012020011001',
            'tgl_lahir' => '1980-01-01',
            'alamat' => 'Jl. Pendidikan No. 123, Kota Baru',
            'jk' => 'L',
            'no_telp' => '081234567890',
            'school_id' => 1,
        ]);

        // 4. Buat Kelas
        $classroom = \App\Models\Classroom::create([
            'tingkat' => 'VII',      // Sesuai enum: VII, VIII, IX
            'paralel' => 'A',        // Sesuai enum: A-H
            'walas_id' => $teacher->id, // Menggunakan ID guru yang dibuat di atas
        ]);

        // 5. Buat Mata Pelajaran
        $subject = \App\Models\Subject::create([
            'nama_mapel' => 'Matematika'
        ]);

        // 6. Buat Jadwal (Agar Guru punya tugas di Dashboard nanti)
        $schedule =  \App\Models\Schedule::create([
            'academic_year_id' => $year->id,
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'classroom_id' => $classroom->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:30:00',
            'jam_habis' => '09:00:00',
        ]);

        // 7. Buat User Admin Utama
        \App\Models\User::create([
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'role' => 'Admin',
        ]);

        $attendance = \App\Models\Attendance::create([
            'schedule_id' => $schedule->id,
            'tanggal' => now()->format('Y-m-d'),
            'academic_year_id' => $year->id, // Sesuai migration tambahan kamu
        ]);

        \App\Models\Evaluation::create([
            'schedule_id' => $schedule->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'jenis' => 'Tugas',
            'nama_penilaian' => 'Tugas Aljabar 1',
            'tanggal' => now()->format('Y-m-d'),
            'academic_year_id' => $year->id, // Sesuai migration tambahan kamu

        ]);

        $this->call([
            StudentSeeder::class,
        ]);
    }
}
