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
    }
}
