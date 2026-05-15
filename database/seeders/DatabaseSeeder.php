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
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | 1. SCHOOL
            |--------------------------------------------------------------------------
            */

            DB::table('schools')->insertOrIgnore([
                'id'           => 1,
                'nama_sekolah' => 'SMPN 1 KOTABARU',
                'alamat'       => 'Jl. Sampel No. 1',
                'no_telp'      => '081234567890',
                'email'        => 'smpn1ktb@gmail.com',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | 2. ACADEMIC YEARS
            |--------------------------------------------------------------------------
            */

            $tahunAjaran = [
                ['tahun' => '2024/2025', 'semester' => 'Ganjil', 'is_active' => false],
                ['tahun' => '2024/2025', 'semester' => 'Genap',  'is_active' => false],
                ['tahun' => '2025/2026', 'semester' => 'Ganjil', 'is_active' => false],
                ['tahun' => '2025/2026', 'semester' => 'Genap',  'is_active' => true],
            ];

            $academicYears = [];

            foreach ($tahunAjaran as $ta) {

                $existing = DB::table('academic_years')
                    ->where('tahun', $ta['tahun'])
                    ->where('semester', $ta['semester'])
                    ->first();

                if ($existing) {
                    $academicYears[] = $existing;
                } else {

                    $id = DB::table('academic_years')->insertGetId([
                        'tahun'      => $ta['tahun'],
                        'semester'   => $ta['semester'],
                        'is_active'  => $ta['is_active'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $academicYears[] = (object)[
                        'id'        => $id,
                        'tahun'     => $ta['tahun'],
                        'semester'  => $ta['semester'],
                        'is_active' => $ta['is_active'],
                    ];
                }
            }

            $activeYear = collect($academicYears)->firstWhere('is_active', true);

            /*
            |--------------------------------------------------------------------------
            | 3. ADMIN
            |--------------------------------------------------------------------------
            */

            DB::table('users')->insertOrIgnore([
                'username'   => 'admin',
                'password'   => Hash::make('admin123'),
                'role'       => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | 4. TEACHERS
            |--------------------------------------------------------------------------
            */

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

            $gelar     = ['S.Pd', 'M.Pd', 'S.Pd.I', 'M.Pd.I'];
            $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'];

            $teacherIds = [];

            for ($i = 1; $i <= 50; $i++) {

                $jk = $i % 2 === 0 ? 'L' : 'P';

                $namaDepan = $jk === 'L'
                    ? $namaDepanLaki[array_rand($namaDepanLaki)]
                    : $namaDepanPerempuan[array_rand($namaDepanPerempuan)];

                $belakang = $namaBelakang[array_rand($namaBelakang)];

                $namaGuru = $namaDepan . ' ' . $belakang . ' ' . $i . ', ' . $gelar[array_rand($gelar)];

                $username = strtolower($namaDepan . $i);

                $user = DB::table('users')
                    ->where('username', $username)
                    ->first();

                if (!$user) {

                    $userId = DB::table('users')->insertGetId([
                        'username'   => $username,
                        'password'   => Hash::make('guru123'),
                        'role'       => 'Guru',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $userId = $user->id;
                }

                $teacher = DB::table('teachers')
                    ->where('user_id', $userId)
                    ->first();

                if ($teacher) {

                    $teacherIds[] = $teacher->id;
                } else {

                    $teacherIds[] = DB::table('teachers')->insertGetId([
                        'user_id'    => $userId,
                        'nama_guru'  => $namaGuru,
                        'nip'        => rand(100000000000000000, 999999999999999999),
                        'jk'         => $jk,
                        'agama'      => $agamaList[array_rand($agamaList)],
                        'tgl_lahir'  => Carbon::create(rand(1970, 1995), rand(1, 12), rand(1, 28)),
                        'alamat'     => 'Jl. Guru No. ' . rand(1, 200),
                        'no_telp'    => '08' . rand(100000000, 999999999),
                        'school_id'  => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 5. SUBJECTS
            |--------------------------------------------------------------------------
            */

            $subjects = [
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

            foreach ($subjects as $subject) {

                $existing = DB::table('subjects')
                    ->where('nama_mapel', $subject)
                    ->first();

                if ($existing) {

                    $subjectIds[] = $existing->id;
                } else {

                    $subjectIds[] = DB::table('subjects')->insertGetId([
                        'nama_mapel' => $subject,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 6. CLASSROOMS
            |--------------------------------------------------------------------------
            */

            $tingkatList = ['VII', 'VIII', 'IX'];
            $paralelList = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

            $classroomIds = [];

            $walasIndex = 0;

            foreach ($tingkatList as $tingkat) {

                foreach ($paralelList as $paralel) {

                    $existing = DB::table('classrooms')
                        ->where('tingkat', $tingkat)
                        ->where('paralel', $paralel)
                        ->first();

                    if ($existing) {

                        $classroomIds[] = $existing->id;
                    } else {

                        $classroomIds[] = DB::table('classrooms')->insertGetId([
                            'tingkat'    => $tingkat,
                            'paralel'    => $paralel,
                            'walas_id'   => $teacherIds[$walasIndex % count($teacherIds)],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    $walasIndex++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 7. STUDENTS
            |--------------------------------------------------------------------------
            */

            $studentIds = [];

            $nisCounter = 10000;

            foreach ($classroomIds as $classroomId) {

                $studentIds[$classroomId] = [];

                $studentBatch = [];

                for ($i = 1; $i <= 40; $i++) {

                    $jk = $i % 2 === 0 ? 'L' : 'P';

                    $namaDepan = $jk === 'L'
                        ? $namaDepanLaki[array_rand($namaDepanLaki)]
                        : $namaDepanPerempuan[array_rand($namaDepanPerempuan)];

                    $nama = $namaDepan . ' ' . $namaBelakang[array_rand($namaBelakang)];

                    $nisCounter++;

                    $studentBatch[] = [
                        'nama'         => $nama,
                        'nis'          => (string)$nisCounter,
                        'jk'           => $jk,
                        'agama'        => 'Islam',
                        'tgl_lahir'    => Carbon::create(rand(2009, 2012), rand(1, 12), rand(1, 28)),
                        'alamat'       => 'Jl. Siswa No. ' . rand(1, 100),
                        'no_telp'      => '08' . rand(100000000, 999999999),
                        'no_telp_ortu' => '08' . rand(100000000, 999999999),
                        'classroom_id' => $classroomId,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ];
                }

                DB::table('students')->insert($studentBatch);

                $ids = DB::table('students')
                    ->where('classroom_id', $classroomId)
                    ->pluck('id')
                    ->toArray();

                $studentIds[$classroomId] = $ids;
            }

            /*
            |--------------------------------------------------------------------------
            | 8. SCHEDULES
            |--------------------------------------------------------------------------
            */

            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

            $jamList = [
                ['jam_mulai' => '07:30', 'jam_habis' => '09:00'],
                ['jam_mulai' => '09:00', 'jam_habis' => '10:30'],
                ['jam_mulai' => '10:30', 'jam_habis' => '12:00'],
                ['jam_mulai' => '13:00', 'jam_habis' => '14:30'],
            ];

            $scheduleIds = [];

            foreach ($academicYears as $academicYear) {

                foreach ($classroomIds as $classroomId) {

                    $scheduleIds[$classroomId] = [];

                    foreach ($hariList as $hari) {

                        foreach ($jamList as $jam) {

                            // random kosong
                            if (rand(1, 100) <= 20) {
                                continue;
                            }

                            $scheduleId = DB::table('schedules')->insertGetId([
                                'academic_year_id' => $academicYear->id,
                                'semester'         => $academicYear->semester,
                                'teacher_id'       => $teacherIds[array_rand($teacherIds)],
                                'subject_id'       => $subjectIds[array_rand($subjectIds)],
                                'classroom_id'     => $classroomId,
                                'hari'             => $hari,
                                'jam_mulai'        => $jam['jam_mulai'],
                                'jam_habis'        => $jam['jam_habis'],
                                'created_at'       => now(),
                                'updated_at'       => now(),
                            ]);

                            $scheduleIds[$classroomId][] = $scheduleId;
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 9. ATTENDANCES
            |--------------------------------------------------------------------------
            */

            $statusList = [
                'Hadir',
                'Hadir',
                'Hadir',
                'Hadir',
                'Izin',
                'Sakit',
                'Alpa'
            ];

            foreach ($scheduleIds as $classroomId => $scheduleList) {

                foreach ($scheduleList as $scheduleId) {

                    $schedule = DB::table('schedules')->find($scheduleId);

                    for ($week = 0; $week < 8; $week++) {

                        // 15% guru tidak absen
                        if (rand(1, 100) <= 15) {
                            continue;
                        }

                        $hariIndex = array_search($schedule->hari, $hariList);

                        $tanggal = Carbon::now()
                            ->subWeeks($week)
                            ->startOfWeek()
                            ->addDays($hariIndex)
                            ->toDateString();

                        $attendanceId = DB::table('attendances')->insertGetId([
                            'schedule_id'      => $scheduleId,
                            'tanggal'          => $tanggal,
                            'academic_year_id' => $schedule->academic_year_id,
                            'semester'         => $schedule->semester,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);

                        $details = [];

                        foreach ($studentIds[$classroomId] as $studentId) {

                            $details[] = [
                                'attendance_id' => $attendanceId,
                                'student_id'    => $studentId,
                                'status'        => $statusList[array_rand($statusList)],
                                'created_at'    => now(),
                                'updated_at'    => now(),
                            ];
                        }

                        DB::table('attendance_details')->insert($details);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 10. EVALUATIONS
            |--------------------------------------------------------------------------
            */

            $jenisEval = ['Tugas', 'UH', 'UTS', 'UAS'];

            foreach ($scheduleIds as $classroomId => $scheduleList) {

                foreach ($scheduleList as $scheduleId) {

                    $schedule = DB::table('schedules')->find($scheduleId);

                    foreach ($jenisEval as $jenis) {

                        $evaluationId = DB::table('evaluations')->insertGetId([
                            'schedule_id'      => $scheduleId,
                            'subject_id'       => $schedule->subject_id,
                            'classroom_id'     => $classroomId,
                            'teacher_id'       => $schedule->teacher_id,
                            'academic_year_id' => $schedule->academic_year_id,
                            'semester'         => $schedule->semester,
                            'jenis'            => $jenis,
                            'nama_penilaian'   => $jenis . ' ' . rand(1, 3),
                            'tanggal'          => now()->subDays(rand(1, 120)),
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);

                        $details = [];

                        foreach ($studentIds[$classroomId] as $studentId) {

                            $roll = rand(1, 100);

                            $nilai = match (true) {
                                $roll <= 10 => rand(40, 59),
                                $roll <= 80 => rand(60, 85),
                                default     => rand(86, 100),
                            };

                            $details[] = [
                                'evaluation_id' => $evaluationId,
                                'student_id'    => $studentId,
                                'nilai'         => $nilai,
                                'created_at'    => now(),
                                'updated_at'    => now(),
                            ];
                        }

                        DB::table('evaluation_details')->insert($details);
                    }
                }
            }

            $this->command->info('Seeder selesai!');
            $this->command->info('Admin -> username: admin | password: admin123');
            $this->command->info('Guru  -> password: guru123');
        });
    }
}
