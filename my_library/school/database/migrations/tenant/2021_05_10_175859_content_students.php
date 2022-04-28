<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContentStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_students', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('content_id');
            $table->unsignedInteger('count')->default(1);
        });

        Schema::table('content_students', function($table) {
            $table->foreign('student_id')->references('id')->on('users');
            $table->foreign('content_id')->references('id')->on('lms_contents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_students');
    }
}
