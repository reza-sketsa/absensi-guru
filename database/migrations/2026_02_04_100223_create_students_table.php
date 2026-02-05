<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('nama', 100);
            $table->enum('agama', ['Islam', 'Kristen', 'Hindu', 'Budhha']);
            $table->enum('jk', ['L', 'P']);
            $table->date('tgl_lahir');
            $table->string('nis', 10)->unique;
            $table->text('alamat');
            $table->string('no_telp', 20);
            $table->foreignId('classroom_id')->constrained('classrooms')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
