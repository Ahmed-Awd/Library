<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->string('title',50);
            $table->string('slug',255);
            $table->string('subject',255);
            $table->text('description');
            $table->boolean('new')->default(true);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('created_by_ip',120)->nullable();
            $table->string('updated_by_ip',120)->nullable();
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->BigInteger('branch_id')->unsigned();
            $table->unsignedBigInteger('updated_by_user')->nullable();
        });

        Schema::table('feedbacks', function($table) {
            $table->foreign('created_by_user')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('updated_by_user')->references('id')->on('users');
            $table->foreign('branch_id')->references('id')->on('branch');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
}
