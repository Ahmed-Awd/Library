<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversitySubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('university_subjects');
        Schema::create('university_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('track_id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('semester_id');
            $table->primary(['subject_id', 'track_id', 'program_id','semester_id','level_id'],'university_subject_unique');
            $table->boolean('mandatory')->default(true);
        });
        Schema::table('university_subjects', function($table) {
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('semester_id')->references('id')->on('academics_semesters');
            $table->foreign('track_id')->references('id')->on('university_tracks');
            $table->foreign('program_id')->references('id')->on('university_programs');
            $table->foreign('level_id')->references('id')->on('university_levels');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('university_subjects');
    }
}
