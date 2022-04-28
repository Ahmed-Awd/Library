<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('profiles');
        Schema::create('profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('admission_no',50);
            $table->string('roll_no',50)->nullable();
            $table->string('job_title',225);
            $table->unsignedBigInteger('user_id');
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_join')->nullable();
            $table->enum('gender',['male','female'])->nullable();
            $table->string('blood_group',255)->nullable();
            $table->string('mothers_name',255)->nullable();
            $table->string('address_lane1',255)->nullable();
            $table->string('address_lane2',255)->nullable();
            $table->string('city',255)->nullable();
            $table->string('state',255)->nullable();
            $table->string('zipcode',255)->nullable();
            $table->string('country',255)->nullable();
            $table->string('mobile',255)->nullable();
            $table->string('home_phone',255)->nullable();
            $table->string('qualification',255)->nullable();
            $table->integer('total_experience_years')->nullable();
            $table->integer('total_experience_month')->nullable();
            $table->text('experience_information')->nullable();
            $table->text('other_information')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('created_by_ip',120)->nullable();
            $table->string('updated_by_ip',120)->nullable();
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->unsignedBigInteger('updated_by_user')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('profiles');
    }
}
