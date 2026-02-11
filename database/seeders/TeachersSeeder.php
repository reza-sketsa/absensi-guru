<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Teacher::create([
            'id' => 1,
            'user_id' => 1,
            'nama_guru' => 'Puspa',
            'agama' => 'Islam',
            'nip' => '008987654',
            'jk' => 'P',
            'alamat' => 'muka SMA GARUDA',
            'no_telp' => '081256877110',
            'school_id' => 1,
        ]);
    }
}
