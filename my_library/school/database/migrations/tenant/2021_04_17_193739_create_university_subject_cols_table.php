<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversitySubjectColsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_subject_cols', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name',120);
            $table->integer('score')->unsigned();
            $table->unsignedBigInteger('config_id');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('created_by_ip',120)->nullable();
            $table->string('updated_by_ip',120)->nullable();
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->unsignedBigInteger('updated_by_user')->nullable();
        });
        Schema::table('university_subject_cols', function($table) {
            $table->foreign('config_id')->references('id')->on('university_subject_configs');
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
        Schema::dropIfExists('university_subject_cols');
    }
}
