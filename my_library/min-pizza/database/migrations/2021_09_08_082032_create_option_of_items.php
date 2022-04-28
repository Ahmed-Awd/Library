<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionOfItems extends Migration
{

    public function up()
    {
        Schema::create('item_option', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('option_id');
            $table->enum('type', ['primary','secondary']);
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('option_id')->references('id')->on('option_categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_option');
    }
}
