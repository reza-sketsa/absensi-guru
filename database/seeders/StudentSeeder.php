<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Student::create([
            'id' => 1,
            'nama' => 'M.Reza Al Farisi',
            'agama' => 'Islam',
            'jk' => 'L',
            'tgl_lahir' => '2008-12-29',
            'nis' => '0081234567',
            'alamat' => 'Jl. Pangeran Kacil',
            'no_telp' => '082254853872',
            'no_telp_ortu' => '081256877110',
            'classroom_id' => 1,
        ]);
    }
}
