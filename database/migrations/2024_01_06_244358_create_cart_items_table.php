<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->foreign('shipping_id')->references('id')->on('shipping_methods')->onDelete('no action');
            $table->unsignedBigInteger('cart_id')->nullable();
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('no action');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('no action');
            $table->unsignedInteger('qty')->nullable();

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
        Schema::dropIfExists('cart_items');
    }
};
