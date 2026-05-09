<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // 1. SCHOOL
        // =========================
        DB::table('schools')->insertOrIgnore([
            'id'           => 1,
            'nama_sekolah' => 'SMPN 1 KOTABARU',
            'alamat'       => 'Jl. Sampel No. 1',
            'no_telp'      => '081234567890',
            'email'        => 'smpn1ktb@gmail.com',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // =========================
        // 2. ACADEMIC YEAR
        // =========================
        $existing = DB::table('academic_years')
            ->where('tahun', '2025/2026')
            ->where('semester', 'Genap')
            ->first();

        $academicYearId = $existing
            ? $existing->id
            : DB::table('academic_years')->insertGetId([
                'tahun'      => '2025/2026',
                'semester'   => 'Genap',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        // =========================
        // 3. ADMIN USER
        // =========================
        DB::table('users')->insertOrIgnore([
            'username'   => 'admin',
            'password'   => Hash::make('admin123'),
            'role'       => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // =========================
        // 4. 50 GURU + USER
        // =========================
        $namaDepanLaki = [
            'Ahmad',
            'Budi',
            'Dian',
            'Eko',
            'Fajar',
            'Gilang',
            'Hendra',
            'Ivan',
            'Joko',
            'Kevin',
            'Luthfi',
            'Maulana',
            'Nanda',
            'Omar',
            'Putra',
            'Reza',
            'Sandi',
            'Taufik',
            'Umar',
            'Vino',
            'Wahyu',
            'Yudi',
            'Zainal',
            'Arif',
            'Bagas'
        ];
        $namaDepanPerempuan = [
            'Aini',
            'Bunga',
            'Citra',
            'Desi',
            'Ella',
            'Fani',
            'Gita',
            'Hana',
            'Indah',
            'Julia',
            'Kiki',
            'Lisa',
            'Maya',
            'Nisa',
            'Putri',
            'Rani',
            'Sari',
            'Tari',
            'Umi',
            'Vivi',
            'Wati',
            'Yuni',
            'Zara',
            'Anisa',
            'Bella'
        ];
        $namaBelakang = [
            'Santoso',
            'Rahayu',
            'Fauzi',
            'Lestari',
            'Pratama',
            'Aini',
            'Hidayat',
            'Susanto',
            'Wijaya',
            'Kurniawan',
            'Setiawan',
            'Nugroho',
            'Purnama',
            'Saputra',
            'Handoko',
            'Wibowo',
            'Suryanto',
            'Gunawan',
            'Hartono',
            'Siregar'
        ];
        $gelar     = ['S.Pd', 'M.Pd', 'S.Pd.I', 'M.Pd.I', 'S.Pd', 'S.Pd'];
        $agamaList = ['Islam', 'Islam', 'Islam', 'Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'];

        $teacherIds = [];

        for ($i = 0; $i < 50; $i++) {
            $jk        = $i % 2 === 0 ? 'L' : 'P';
            $namaDepan = $jk === 'L'
                ? $namaDepanLaki[$i % count($namaDepanLaki)]
                : $namaDepanPerempuan[$i % count($namaDepanPerempuan)];
            $belakang  = $namaBelakang[$i % count($namaBelakang)];
            $namaGuru  = $namaDepan . ' ' . $belakang . ', ' . $gelar[$i % count($gelar)];
            $username  = strtolower(str_replace(' ', '', $namaDepan) . '.' . str_replace(' ', '', $belakang) . ($i + 1));

            $existingUser = DB::table('users')->where('username', $username)->first();
            if ($existingUser) {
                $userId = $existingUser->id;
            } else {
                $userId = DB::table('users')->insertGetId([
                    'username'   => $username,
                    'password'   => Hash::make('guru123'),
                    'role'       => 'Guru',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $existingTeacher = DB::table('teachers')->where('user_id', $userId)->first();
            if ($existingTeacher) {
                $teacherIds[] = $existingTeacher->id;
            } else {
                $nip = '19' . rand(70, 99) . str_pad(rand(1, 9), 2, '0', STR_PAD_LEFT) . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT) . '20' . rand(18, 23) . '0' . ($jk === 'L' ? '1' : '2') . '00' . str_pad($i + 1, 2, '0', STR_PAD_LEFT);

                $teacherIds[] = DB::table('teachers')->insertGetId([
                    'user_id'    => $userId,
                    'nama_guru'  => $namaGuru,
                    'nip'        => substr($nip, 0, 20),
                    'jk'         => $jk,
                    'agama'      => $agamaList[$i % count($agamaList)],
                    'tgl_lahir'  => Carbon::create(rand(1970, 1995), rand(1, 12), rand(1, 28))->format('Y-m-d'),
                    'alamat'     => 'Jl. Guru No. ' . ($i + 1) . ', Kotabaru',
                    'no_telp'    => '08' . rand(100000000, 999999999),
                    'school_id'  => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // =========================
        // 5. SUBJECTS
        // =========================
        $subjectNames = [
            'Matematika',
            'IPA',
            'IPS',
            'PKN',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Agama Islam',
            'Seni Budaya',
            'PJOK',
            'Prakarya'
        ];
        $subjectIds = [];
        foreach ($subjectNames as $nama) {
            $existing     = DB::table('subjects')->where('nama_mapel', $nama)->first();
            $subjectIds[] = $existing
                ? $existing->id
                : DB::table('subjects')->insertGetId([
                    'nama_mapel' => $nama,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
        }

        // =========================
        // 6. CLASSROOMS (VII-A sd IX-H = 24 kelas)
        // =========================
        $tingkatList  = ['VII', 'VIII', 'IX'];
        $paralelList  = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $classroomIds = [];
        $walisIndex   = 0;

        foreach ($tingkatList as $tingkat) {
            foreach ($paralelList as $paralel) {
                $existing       = DB::table('classrooms')
                    ->where('tingkat', $tingkat)
                    ->where('paralel', $paralel)
                    ->first();
                $classroomIds[] = $existing
                    ? $existing->id
                    : DB::table('classrooms')->insertGetId([
                        'tingkat'    => $tingkat,
                        'paralel'    => $paralel,
                        'walas_id'   => $teacherIds[$walisIndex % count($teacherIds)],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                $walisIndex++;
            }
        }

        // =========================
        // 7. STUDENTS (15 per kelas)
        // =========================
        $namaLaki = [
            'Ahmad',
            'Budi',
            'Dian',
            'Eko',
            'Fajar',
            'Gilang',
            'Hendra',
            'Ivan',
            'Joko',
            'Kevin',
            'Luthfi',
            'Maulana',
            'Nanda',
            'Omar',
            'Putra',
            'Reza',
            'Sandi',
            'Taufik',
            'Umar',
            'Wahyu'
        ];
        $namaPerempuan = [
            'Aini',
            'Bunga',
            'Citra',
            'Desi',
            'Ella',
            'Fani',
            'Gita',
            'Hana',
            'Indah',
            'Julia',
            'Kiki',
            'Lisa',
            'Maya',
            'Nisa',
            'Putri',
            'Rani',
            'Sari',
            'Tari',
            'Umi',
            'Vivi'
        ];
        $namaBelakangSiswa = [
            'Santoso',
            'Rahayu',
            'Pratama',
            'Lestari',
            'Wijaya',
            'Kurnia',
            'Saputra',
            'Hidayat',
            'Nugroho',
            'Purnama',
            'Susanto',
            'Wibowo',
            'Gunawan',
            'Hartono',
            'Siregar'
        ];

        $studentIds = [];
        $nisCounter = 10000;

        foreach ($classroomIds as $classroomId) {
            $studentIds[$classroomId] = [];
            for ($i = 0; $i < 40; $i++) {
                $jk        = $i % 2 === 0 ? 'L' : 'P';
                $namaDepan = $jk === 'L'
                    ? $namaLaki[$i % count($namaLaki)]
                    : $namaPerempuan[$i % count($namaPerempuan)];
                $nisCounter++;

                $existingStudent = DB::table('students')->where('nis', (string) $nisCounter)->first();
                if ($existingStudent) {
                    $studentIds[$classroomId][] = $existingStudent->id;
                    continue;
                }

                $studentIds[$classroomId][] = DB::table('students')->insertGetId([
                    'nama'         => $namaDepan . ' ' . $namaBelakangSiswa[$i % count($namaBelakangSiswa)],
                    'nis'          => (string) $nisCounter,
                    'jk'           => $jk,
                    'agama'        => 'Islam',
                    'tgl_lahir'    => Carbon::create(rand(2009, 2012), rand(1, 12), rand(1, 28))->format('Y-m-d'),
                    'alamat'       => 'Jl. Siswa No. ' . rand(1, 100) . ', Kotabaru',
                    'no_telp'      => '08' . rand(100000000, 999999999),
                    'no_telp_ortu' => '08' . rand(100000000, 999999999),
                    'classroom_id' => $classroomId,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }

        // =========================
        // 8. SCHEDULES
        // =========================
        $hariList     = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jamList      = [
            ['jam_mulai' => '07:30', 'jam_habis' => '09:00'],
            ['jam_mulai' => '09:00', 'jam_habis' => '10:30'],
            ['jam_mulai' => '10:30', 'jam_habis' => '12.00'],
            ['jam_mulai' => '13.00', 'jam_habis' => '14.00'],
        ];
        $scheduleIds  = [];
        $teacherIndex = 0;
        $subjectIndex = 0;

        foreach ($classroomIds as $classroomId) {
            $scheduleIds[$classroomId] = [];
            foreach ($hariList as $hariIdx => $hari) {
                $jam      = $jamList[$hariIdx % count($jamList)];
                $existing = DB::table('schedules')
                    ->where('classroom_id', $classroomId)
                    ->where('hari', $hari)
                    ->where('jam_mulai', $jam['jam_mulai'])
                    ->first();

                $scheduleIds[$classroomId][] = $existing
                    ? $existing->id
                    : DB::table('schedules')->insertGetId([
                        'academic_year_id' => $academicYearId,
                        'teacher_id'       => $teacherIds[$teacherIndex % count($teacherIds)],
                        'subject_id'       => $subjectIds[$subjectIndex % count($subjectIds)],
                        'classroom_id'     => $classroomId,
                        'hari'             => $hari,
                        'jam_mulai'        => $jam['jam_mulai'],
                        'jam_habis'        => $jam['jam_habis'],
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                $teacherIndex++;
                $subjectIndex++;
            }
        }

        // =========================
        // 9. ATTENDANCES + DETAILS
        // =========================
        $statusList = ['Hadir', 'Hadir', 'Hadir', 'Hadir', 'Hadir', 'Izin', 'Sakit', 'Alpa'];

        for ($week = 0; $week < 4; $week++) {
            foreach ($classroomIds as $classroomId) {
                foreach ($scheduleIds[$classroomId] as $scheduleId) {
                    $schedule  = DB::table('schedules')->find($scheduleId);
                    $hariIndex = array_search($schedule->hari, $hariList);
                    $tanggal   = Carbon::now()->subWeeks($week)->startOfWeek()->addDays($hariIndex)->toDateString();

                    $existing = DB::table('attendances')
                        ->where('schedule_id', $scheduleId)
                        ->where('tanggal', $tanggal)
                        ->first();

                    if ($existing) continue;

                    $attendanceId = DB::table('attendances')->insertGetId([
                        'schedule_id'      => $scheduleId,
                        'tanggal'          => $tanggal,
                        'academic_year_id' => $academicYearId,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                    foreach ($studentIds[$classroomId] as $studentId) {
                        DB::table('attendance_details')->insert([
                            'attendance_id' => $attendanceId,
                            'student_id'    => $studentId,
                            'status'        => $statusList[array_rand($statusList)],
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]);
                    }
                }
            }
        }

        // =========================
        // 10. EVALUATIONS + DETAILS
        // =========================
        $jenisEval = ['Tugas', 'UH', 'UTS', 'UAS'];
        $evalNames = [
            'Tugas' => ['Tugas 1', 'Tugas 2', 'Tugas 3'],
            'UH'    => ['UH 1', 'UH 2'],
            'UTS'   => ['UTS Genap'],
            'UAS'   => ['UAS Genap'],
        ];

        foreach ($classroomIds as $classroomId) {
            foreach ($scheduleIds[$classroomId] as $scheduleId) {
                $schedule = DB::table('schedules')->find($scheduleId);

                foreach ($jenisEval as $jenis) {
                    foreach ($evalNames[$jenis] as $namaEval) {
                        $existing = DB::table('evaluations')
                            ->where('schedule_id', $scheduleId)
                            ->where('jenis', $jenis)
                            ->where('nama_penilaian', $namaEval)
                            ->first();

                        if ($existing) continue;

                        $evaluationId = DB::table('evaluations')->insertGetId([
                            'schedule_id'      => $scheduleId,
                            'subject_id'       => $schedule->subject_id,
                            'classroom_id'     => $classroomId,
                            'teacher_id'       => $schedule->teacher_id,
                            'academic_year_id' => $academicYearId,
                            'jenis'            => $jenis,
                            'nama_penilaian'   => $namaEval,
                            'tanggal'          => Carbon::now()->subDays(rand(1, 90))->format('Y-m-d'),
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);

                        foreach ($studentIds[$classroomId] as $studentId) {
                            DB::table('evaluation_details')->insert([
                                'evaluation_id' => $evaluationId,
                                'student_id'    => $studentId,
                                'nilai'         => rand(60, 100),
                                'created_at'    => now(),
                                'updated_at'    => now(),
                            ]);
                        }
                    }
                }
            }
        }

        $this->command->info('Seeder selesai!');
        $this->command->info('Admin -> username: admin    | password: admin123');
        $this->command->info('Guru  -> username: [nama]   | password: guru123');
    }
}
