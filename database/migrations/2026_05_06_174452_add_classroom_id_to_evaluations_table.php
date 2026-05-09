<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            // Tambahkan nullable() agar tidak error jika sudah ada data lama di tabel
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->cascadeOnDelete()->after('subject_id');
        });
    }

    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
        });
    }
};
