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
        Schema::create('repository_shippings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('no action');
            $table->json('products')->nullable();
            $table->unsignedBigInteger('from_repo_id')->nullable();
            $table->foreign('from_repo_id')->references('id')->on('repositories')->onDelete('no action');
            $table->string('from_repo_name', 200)->nullable();
            $table->string('from_repo_phone', 20)->nullable();
            $table->unsignedSmallInteger('from_district_id')->nullable();
            $table->foreign('from_district_id')->references('id')->on('cities')->onDelete('no action');
            $table->string('from_address', 2048)->nullable();

            $table->unsignedBigInteger('to_repo_id')->nullable();
            $table->foreign('to_repo_id')->references('id')->on('repositories')->onDelete('no action');

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('admins')->onDelete('no action');
            $table->string('driver_name', 200)->nullable();
            $table->string('driver_phone', 20)->nullable();

            $table->unsignedBigInteger('car_id')->nullable();
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('no action');
            $table->string('car_name', 200)->nullable();
            $table->string('car_plate', 20)->nullable();
            $table->enum('status', array_column(Variable::SHIPPING_STATUSES, 'name'))->nullable();

            $table->unsignedInteger('shipping_price')->default(0);
            $table->unsignedInteger('product_price')->default(0);

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
        Schema::dropIfExists('repository_shippings');
    }
};
