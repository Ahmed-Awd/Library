<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->json('item');
            $table->string('name');
            $table->decimal('price', 20, 2);
            $table->decimal('unit_price', 20, 2);
            $table->decimal('total', 20, 2);
            $table->integer('quantity');
            $table->json('tax');
            // primary option value id
            $table->unsignedBigInteger('primary_option_value_id')->nullable();
            // array of secondary option id of template
            $table->json('template_secondary_options')->nullable();
            // array of  quantity  secondary option id of template
            $table->json('template_secondary_options_quantity')->nullable();
            // object content of  primary_option_value , quantity , total_price
            //and secondary_options_details content of object secondary option value , quantity , total_price
            $table->json('template_selected_options')->nullable();
            // if use options array of object option_value ,quantity , total_price
            $table->json('selected_options')->nullable();
            $table->foreign('primary_option_value_id')->references('id')->on('option_values');
            $table->foreign('order_id')->references('id')->on('orders');
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
        Schema::dropIfExists('order_items');
    }
}
