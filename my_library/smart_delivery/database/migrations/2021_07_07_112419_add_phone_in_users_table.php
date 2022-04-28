<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->integer('phone')->nullable();
            $table->integer('country_code')->nullable();
            $table->dateTime('mobile_verified_at', $precision = 0)->nullable();
            $table->unique(['country_code','phone']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('phone');
            $table->dropColumn('country_code');
            $table->dropColumn('mobile_verified_at');
            $table->dropUnique('table_country_code_phone_unique');
        });
    }
}
