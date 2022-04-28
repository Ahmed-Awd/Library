<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->enum('type', ['rate','fixed']);
            $table->unsignedSmallInteger('amount');
            $table->unsignedSmallInteger('usage_left');
            $table->unsignedSmallInteger('max_usage');
            $table->unsignedInteger('min_order_price');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('restaurant_id')->nullable()->default(null);
            $table->date('start_date');
            $table->date('end_date');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('set null');

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
        Schema::dropIfExists('discount_codes');
    }
}
