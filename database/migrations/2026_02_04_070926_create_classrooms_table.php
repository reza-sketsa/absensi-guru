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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->enum('tingkat', ['VII', 'VIII', 'IX']);
            $table->enum('paralel', ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']);
            $table->foreignId('walas_id')->constrained('teachers')->restrictOnDelete();
            $table->unique(['tingkat', 'paralel']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
