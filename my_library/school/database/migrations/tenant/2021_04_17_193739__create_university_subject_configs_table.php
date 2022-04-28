<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversitySubjectConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_subject_configs', function (Blueprint $table) {
           $table->id()->unsigned();
           $table->unsignedBigInteger('subject_id');
           $table->unsignedBigInteger('semester_id');
           $table->integer('total')->default(0);
           $table->integer('passing_grade')->default(0);
           $table->boolean('is_published')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('created_by_ip',120)->nullable();
            $table->string('updated_by_ip',120)->nullable();
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->unsignedBigInteger('updated_by_user')->nullable();
        });
        Schema::table('university_subject_configs', function($table) {
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('semester_id')->references('id')->on('academics_semesters');
            $table->foreign('created_by_user')->references('id')->on('users');
            $table->foreign('updated_by_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('university_subject_configs');
    }
}
