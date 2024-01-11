<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repo_id')->nullable();
            $table->foreign('repo_id')->references('id')->on('repositories')->onDelete('no action');
            $table->json('products')->nullable();
            $table->json('cities')->nullable();
            $table->unsignedInteger('per_weight_price')->default(0);
            $table->unsignedInteger('base_price')->default(0);
            $table->unsignedInteger('free_from_price')->nullable();
            $table->string('name', 200);
            $table->string('description', 2048)->nullable();
            $table->timestamps();
        });

        DB::table('shipping_methods')->insert(\App\Http\Helpers\Variable::getDefaultShippingMethods());

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_methods');
    }
};
