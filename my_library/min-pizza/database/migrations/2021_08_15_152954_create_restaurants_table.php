<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('name', 200);
            $table->string('logo', 200)->default('min_pizza.png');
            $table->string('image', 200)->default('min_pizza.png');
            $table->string('company_name', 200)->nullable();
            $table->string('company_number', 200)->nullable();
            $table->string('vat', 50)->nullable();
            $table->decimal('lat', 11, 8);
            $table->decimal('lng', 11, 8);
            $table->text('address')->nullable();
            $table->unsignedBigInteger('min_order_price')->nullable();
            $table->string('ZIP_code', 50)->nullable();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('restaurant_statuses');
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
        Schema::dropIfExists('restaurants');
    }
}
