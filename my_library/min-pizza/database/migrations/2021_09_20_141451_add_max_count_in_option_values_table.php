<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxCountInOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_values', function (Blueprint $table) {
            $table->integer('min_count')->default(1);
            $table->integer('max_count')->default(1);
            $table->boolean('is_available')->default(1);
            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_values', function (Blueprint $table) {
            //
            $table->dropColumn('min_count');
            $table->dropColumn('max_count');
            $table->dropColumn('is_available');
            $table->dropColumn('status');
        });
    }
}
