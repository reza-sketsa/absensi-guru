<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SchoolSeeder::class,
            UserSeeder::class,
            TeachersSeeder::class,
            ClassroomSeeder::class,
            StudentSeeder::class,
        ]);

        //     $user = User::firstOrCreate(
        //         ['username' => 'admin'],
        //         [
        //             'password' => bcrypt('admin'),
        //             'role' => 'Admin',
        //         ]
        //     );

        //     $schoolId = DB::table('schools')
        //         ->where('email', 'admin@school.test')
        //         ->value('id');

        //     if (! $schoolId) {
        //         $schoolId = DB::table('schools')->insertGetId([
        //             'nama_sekolah' => 'Sekolah Contoh',
        //             'alamat' => 'Jl. Contoh No. 1',
        //             'no_telp' => '080000000000',
        //             'email' => 'admin@school.test',
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }

        //     $exists = DB::table('teachers')
        //         ->where('user_id', $user->id)
        //         ->exists();

        //     if (! $exists) {
        //         DB::table('teachers')->insert([
        //             'user_id' => $user->id,
        //             'nama_guru' => 'Hendri Arifin',
        //             'nip' => '000000000000000001',
        //             'jk' => 'L',
        //             'alamat' => 'Jl. Contoh No. 1',
        //             'no_telp' => '085746080544',
        //             'school_id' => $schoolId,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     }
    }
}
