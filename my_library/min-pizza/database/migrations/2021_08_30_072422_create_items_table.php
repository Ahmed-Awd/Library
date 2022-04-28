<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->string('image', 220)->default('min_pizza.png');
            $table->decimal('price', 6, 2)->default(0.00);
            $table->boolean('is_available')->default(true);
            $table->unsignedSmallInteger('alcohol_percentage')->default(0);
            $table->foreign('category_id')->references('id')->on('menu_categories');
            $table->foreign('tax_id')->references('id')->on('taxes');
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
        Schema::dropIfExists('items');
    }
}
