<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRestaurantIdInUsersTable extends Migration
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
            $table->string('mongo_db_restaurant_id')->nullable();
            $table->string('mongo_db_id')->nullable();
        });
        Schema::table('restaurants', function (Blueprint $table) {
            //
            $table->string('mongo_db_id')->nullable();
        });
        Schema::table('option_categories', function (Blueprint $table) {
            //
            $table->string('mongo_db_id')->nullable();
        });
        Schema::table('option_values', function (Blueprint $table) {
            //
            $table->string('mongo_db_id')->nullable();
        });
        Schema::table('items', function (Blueprint $table) {
            //
            $table->string('mongo_db_id')->nullable();
        });
        Schema::table('categories', function (Blueprint $table) {
            //
            $table->string('mongo_db_id')->nullable();
        });
        Schema::table('taxes', function (Blueprint $table) {
            //
            $table->string('mongo_db_id')->nullable();
        });
        Schema::table('option_templates', function (Blueprint $table) {
            //
            $table->string('mongo_db_id')->nullable();
        });
        Schema::table('option_secondaries', function (Blueprint $table) {
            //
            $table->string('mongo_db_id')->nullable();
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
            $table->dropColumn('mongo_db_restaurant_id');
            $table->dropColumn('mongo_db_id');
        });
        Schema::table('restaurants', function (Blueprint $table) {
            //
            $table->dropColumn('mongo_db_id');
        });
        Schema::table('option_categories', function (Blueprint $table) {
            //
            $table->dropColumn('mongo_db_id');
        });
        Schema::table('option_values', function (Blueprint $table) {
            //
            $table->dropColumn('mongo_db_id');
        });
        Schema::table('items', function (Blueprint $table) {
            //
            $table->dropColumn('mongo_db_id');
        });
        Schema::table('categories', function (Blueprint $table) {
            //
            $table->dropColumn('mongo_db_id');
        });
        Schema::table('taxes', function (Blueprint $table) {
            //
            $table->dropColumn('mongo_db_id');
        });
        Schema::table('option_templates', function (Blueprint $table) {
            //
            $table->dropColumn('mongo_db_id');
        });
        Schema::table('option_secondaries', function (Blueprint $table) {
            //
            $table->dropColumn('mongo_db_id');
        });
    }
}
