<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversityTrackLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_track_levels', function (Blueprint $table) {
            $table->unsignedBigInteger('track_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('program_id');
            $table->primary(['track_id', 'level_id', 'program_id']);
        });
        Schema::table('university_track_levels', function($table) {
            $table->foreign('track_id')->references('id')->on('university_tracks');
            $table->foreign('level_id')->references('id')->on('university_levels');
            $table->foreign('program_id')->references('id')->on('university_programs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('university_track_levels');
    }
}
