<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_number');
            $table->foreignId('store_id');
            $table->decimal('customer_lng', 11, 8);
			$table->decimal('customer_lat', 10, 8);
            $table->string('customer_address', 250)->default('');
			$table->integer('building_no')->nullable();
            $table->integer('apartment_no')->nullable();
			$table->string('customer_name', 191)->default('');
			$table->string('customer_mobile', 20);
			$table->integer('total_price')->default(0);
			$table->integer('delivery_price')->default(0);
            $table->integer('distance_store_order');
            $table->integer('qr_code');
            $table->dateTime('expected_time', $precision = 0);
			$table->string('comment')->default('');
			$table->integer('status')->default(1);
			$table->integer('rate')->default(0);
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('users');
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
        Schema::dropIfExists('orders');
    }
}
