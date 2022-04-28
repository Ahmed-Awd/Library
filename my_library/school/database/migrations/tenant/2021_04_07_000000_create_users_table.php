<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name',255);
            $table->string('username',255);
            $table->string('id_number',150);
            $table->string('email',255)->unique();
            $table->string('password',255);
            $table->boolean('is_suspended');
            $table->BigInteger('category_id')->unsigned();
            $table->integer('language_id');
            $table->boolean('status');
            $table->string('image',255);
            $table->string('phone',50);
            $table->text('address');
            $table->string('remember_token', 100);
            $table->string('nationality', 20);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('created_by_ip',120)->nullable();
            $table->string('updated_by_ip',120)->nullable();
            $table->unsignedBigInteger('created_by_user')->nullable();
            $table->unsignedBigInteger('updated_by_user')->nullable();
            $table->BigInteger('branch_id')->unsigned();
            $table->timestamp('email_verified_at')->nullable();
        });
        Schema::table('users', function($table) {
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('branch_id')->references('id')->on('branch');
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
        Schema::dropIfExists('users');
    }
}
