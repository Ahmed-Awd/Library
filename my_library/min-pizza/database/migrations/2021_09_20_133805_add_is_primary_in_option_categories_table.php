<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPrimaryInOptionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_categories', function (Blueprint $table) {
            //
            $table->boolean('is_primary')->default(1);
            $table->integer('max_selectable')->default(1);
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->foreign('restaurant_id')->references('id')->on('restaurants');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('option_categories', function (Blueprint $table) {
            $table->dropForeign('option_categories_restaurant_id_foreign');
            $table->dropColumn('is_primary');
            $table->dropColumn('max_selectable');
            $table->dropColumn('status');
            $table->dropColumn('restaurant_id');
        });
    }
}
