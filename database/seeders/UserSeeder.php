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
            'id' => 1,
            'username' => 'Puspa',
            'password' => bcrypt('123'),
            'role' => 'Guru',
            'remember_token' => Str::random(10),
        ]);
    }
}
