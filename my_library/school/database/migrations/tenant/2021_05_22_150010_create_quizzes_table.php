<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('title',100);
            $table->text('description');
            $table->string('slug',255);
            $table->enum('quiz_type', ['students','class','all']);
            $table->BigInteger('subject_id')->unsigned();
            $table->BigInteger('semester_id')->unsigned();
            $table->BigInteger('teacher_id')->unsigned();
            $table->BigInteger('instruction_id')->unsigned();
            $table->boolean('is_random')->default(false);
            $table->boolean('move_forward')->default(true);
            $table->boolean('show_exam')->default(false);
            $table->boolean('publish_results_immediately')->default(false);
            $table->boolean('view_result')->default(false);
            $table->boolean('multiple_entries')->default(false);
            $table->smallInteger('duration')->unsigned();
            $table->smallInteger('pass_percentage')->unsigned();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('created_by_ip',120)->nullable();
            $table->string('updated_by_ip',120)->nullable();
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->unsignedBigInteger('updated_by_user')->nullable();
            $table->BigInteger('branch_id')->unsigned()->nullable();
        });

        Schema::table('quizzes', function($table) {
            $table->foreign('semester_id')->references('id')->on('academics_semesters');
            $table->foreign('branch_id')->references('id')->on('branch');
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('created_by_user')->references('id')->on('users');
            $table->foreign('updated_by_user')->references('id')->on('users');
            $table->foreign('teacher_id')->references('id')->on('users');
            $table->foreign('instruction_id')->references('id')->on('instructions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
}
