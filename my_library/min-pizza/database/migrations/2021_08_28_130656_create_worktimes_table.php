<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorktimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worktimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('day_id');
            $table->unsignedBigInteger('restaurant_id');
            $table->time('open_from');
            $table->time('open_to');
            $table->boolean('takeaway')->default(0);
            $table->boolean('delivery')->default(0);
            $table->boolean('status')->default(1);
            $table->foreign('day_id')->references('id')->on('days');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->timestamps();
            $table->softDeletes();   
             });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('worktimes');
    }
}
