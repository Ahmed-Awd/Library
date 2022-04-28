<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name',220);
            $table->text('description');
            $table->string('slug',255);
            $table->BigInteger('parent_id')->unsigned()->nullable();
            $table->BigInteger('subject_id')->unsigned();
            $table->BigInteger('semester_id')->unsigned();
            $table->boolean('multi_branch')->default(1);
            $table->string('created_method')->default('normal');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('created_by_ip',120)->nullable();
            $table->string('updated_by_ip',120)->nullable();
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->unsignedBigInteger('updated_by_user')->nullable();
            $table->BigInteger('branch_id')->unsigned()->nullable();
        });
        Schema::table('topics', function($table) {
            $table->foreign('parent_id')->references('id')->on('topics');
            $table->foreign('semester_id')->references('id')->on('academics_semesters');
            $table->foreign('branch_id')->references('id')->on('branch');
            $table->foreign('subject_id')->references('id')->on('subjects');
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
        Schema::dropIfExists('topics');
    }
}
