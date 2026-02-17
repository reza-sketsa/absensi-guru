<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        \App\Models\User::create([
            'username' => 'Admin',
            'password' => bcrypt('123'),
            'role'     => 'Admin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        // 2. Data Guru (5 orang)
        for ($i = 0; $i < 5; $i++) {
            $nama = $faker->name;
            $firstName = explode(' ', $nama)[0];

            // Buat akun User untuk login guru
            $user = \App\Models\User::create([
                'username' => strtolower($firstName) . $faker->numerify('###'),
                'password' => bcrypt('123'),
                'role'     => 'Guru',
            ]);

            \App\Models\Teacher::create([
                'user_id'   => $user->id,
                'school_id' => 1,
                'nama_guru' => $nama,
                'nip'       => $faker->unique()->numerify('##################'),
                'tgl_lahir' => $faker->date(),
                'agama'     => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu']),
                'jk'        => $faker->randomElement(['L', 'P']),
                'alamat'    => $faker->address,
                'no_telp'   => $faker->phoneNumber,
            ]);
        }
    }
}
