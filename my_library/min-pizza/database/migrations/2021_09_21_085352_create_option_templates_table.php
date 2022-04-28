<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_templates', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->unsignedBigInteger('primary_option_id');
            $table->unsignedBigInteger('restaurant_id');
            $table->boolean('is_available')->default(1);
            $table->boolean('status')->default(1);
            $table->timestamps();            
            $table->softDeletes();
            $table->foreign('primary_option_id')->references('id')->on('option_categories');
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
        Schema::dropIfExists('option_templates');
    }
}
