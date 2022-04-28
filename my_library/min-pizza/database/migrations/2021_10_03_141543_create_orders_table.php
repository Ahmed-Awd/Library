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
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('address');
            $table->decimal('delivery_fee', 20, 2);
            $table->decimal('minimum_order_adjustment_amount', 20, 2);
            $table->unsignedBigInteger('preparation_time')->nullable();
            $table->unsignedBigInteger('delivery_time')->nullable();
            $table->decimal('total_amount', 20, 2);
            $table->decimal('total_discount', 20, 2);
            $table->decimal('sub_total', 20, 2);
            $table->string('comment')->default('');
            $table->integer('order_status_id')->default(1);
            $table->integer('payment_method')->default(1);
            $table->integer('order_type')->default(1);
            $table->integer('service_info_type')->default(0);
            $table->integer('rate')->default(0);
            $table->boolean('is_rated')->default(0);
            $table->integer('comission_amount')->default(0);
            $table->integer('comission_percentage')->default(0);
            $table->unsignedBigInteger('driver_id')->nullable();

            $table->foreign('driver_id')->references('id')->on('users');
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::dropIfExists('orders');
    }
}
