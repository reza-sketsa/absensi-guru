<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        \App\Models\User::create([
            'username'       => 'Admin',
            'password'       => bcrypt('123'),
            'role'           => 'Admin',
            'remember_token' => Str::random(10),
        ]);

        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 5; $i++) {
            $nama = $faker->name;
            $firstName = explode(' ', $nama)[0];

            // 1. Bikin Akun User dulu otomatis
            $user = \App\Models\User::create([
                'username' => strtolower($firstName) . $faker->numerify('###'), // Nama jadi username (kecil semua & tanpa spasi)
                'password' => bcrypt('password123'),
                'role'     => 'Guru',
            ]);

            // 2. Bikin Data Guru yang nyambung ke user_id tadi
            \App\Models\Teacher::create([
                'user_id'   => $user->id,
                'school_id' => 1, // <--- TAMBAHIN INI
                'nama_guru' => $nama,
                'nip'       => $faker->unique()->numerify('##########'),
                'tgl_lahir' => $faker->date(),
                'agama'     => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu']),
                'jk'        => $faker->randomElement(['L', 'P']),
                'alamat'    => $faker->address,   // <--- TAMBAHIN INI
                'no_telp'   => $faker->phoneNumber, // <--- TAMBAHIN INI
            ]);
        }
    }
}
