<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_class_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('from_classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->foreignId('to_classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->enum('jenis', ['naik_kelas', 'pindah', 'lulus', 'keluar']); // jenis perpindahan
            $table->text('keterangan')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete(); // admin yang proses
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_class_histories');
    }
};
