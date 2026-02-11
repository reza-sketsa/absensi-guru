<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Classroom::create([
            'id' => 1,
            'tingkat' => 'IX',
            'paralel' => 'C',
            'walas_id' => 1,
        ]);
    }
}
