<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Menambah kolom no_telp_ortu tipe string, maksimal 20 karakter
            // nullable() supaya data lama yang sudah ada tidak error
            // after('no_telp') supaya letaknya berurutan di database
            $table->string('no_telp_ortu', 20)->nullable()->after('no_telp');
        });
    }

    /**
     * Batalkan perubahan (Rollback).
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('no_telp_ortu');
        });
    }
};
