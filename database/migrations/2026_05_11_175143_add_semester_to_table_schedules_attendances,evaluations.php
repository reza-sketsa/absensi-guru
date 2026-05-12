<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_semester_to_tables.php

    public function up()
    {
        $tables = ['schedules', 'attendances', 'evaluations'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->enum('semester', ['Ganjil', 'Genap'])
                    ->after('academic_year_id')
                    ->nullable(); // nullable dulu karena data dummy
            });
        }
    }

    public function down()
    {
        $tables = ['schedules', 'attendances', 'evaluations'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('semester');
            });
        }
    }
};
