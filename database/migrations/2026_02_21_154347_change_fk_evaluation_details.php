<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('evaluation_details', function (Blueprint $table) {
            $table->dropForeign(['evaluation_id']);

            $table->foreign('evaluation_id')
                ->references('id')
                ->on('evaluations')
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('evaluation_details', function (Blueprint $table) {
            $table->dropForeign(['evaluation_id']);

            $table->foreign('evaluation_id')
                ->references('id')
                ->on('evaluations')
                ->restrictOnDelete();
        });
    }
};
