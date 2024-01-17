<?php

use App\Http\Helpers\Variable;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index();
            $table->unsignedSmallInteger('province_id')->nullable();
            $table->foreign('province_id')->references('id')->on('cities')->onDelete('no action');
            $table->unsignedSmallInteger('county_id')->nullable();
            $table->foreign('county_id')->references('id')->on('cities')->onDelete('no action');
            $table->unsignedSmallInteger('district_id')->nullable();
            $table->foreign('district_id')->references('id')->on('cities')->onDelete('no action');
            $table->string('receiver_fullname', 200)->nullable();
            $table->string('receiver_phone', 20)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('address', 2048)->nullable();
            $table->enum('status', array_column(Variable::ORDER_STATUS, 'name'))->index();
            $table->unsignedInteger('total_items')->default(0);
            $table->unsignedBigInteger('total_price')->default(0);
            $table->unsignedBigInteger('total_items_price')->default(0);
            $table->unsignedBigInteger('total_shipping_price')->default(0);
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
};
