<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_contents', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('slug',255);
            $table->string('title',220);
            $table->string('image',255);
            $table->BigInteger('topic_id')->unsigned()->nullable();
            $table->BigInteger('course_id')->unsigned()->nullable();
            $table->enum('content_type', ['file','video','audio','url','video_url','iframe','image_file','meeting_url']);
            $table->text('file_path');
            $table->text('description');
            $table->boolean('multi_branch')->default(0);
            $table->boolean('is_published')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('created_by_ip',120)->nullable();
            $table->string('updated_by_ip',120)->nullable();
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->unsignedBigInteger('updated_by_user')->nullable();
            $table->BigInteger('branch_id')->unsigned()->nullable();
        });

        Schema::table('lms_contents', function($table) {
            $table->foreign('topic_id')->references('id')->on('topics');
            $table->foreign('branch_id')->references('id')->on('branch');
            $table->foreign('course_id')->references('id')->on('courses');
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
        Schema::dropIfExists('lms_contents');
    }
}
