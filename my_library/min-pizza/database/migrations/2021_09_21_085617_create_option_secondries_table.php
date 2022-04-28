<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionSecondriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_secondaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('secondary_option_id');
            $table->unsignedBigInteger('primary_option_value_id');
            $table->unsignedBigInteger('secondary_option_value_id');
            $table->unsignedBigInteger('option_template_id');
            $table->decimal('price', 6, 2);
            $table->boolean('use_default')->default(1);
            $table->boolean('status')->default(1);
            $table->timestamps();            
            $table->foreign('secondary_option_id')->references('id')->on('option_categories');
            $table->foreign('primary_option_value_id')->references('id')->on('option_values');
            $table->foreign('secondary_option_value_id')->references('id')->on('option_values');
            $table->foreign('option_template_id')->references('id')->on('option_templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_secondaries');
    }
}
