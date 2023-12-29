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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('p_products')->onDelete('no action');
            $table->enum('grade', \App\Http\Helpers\Variable::GRADES)->nullable();
            $table->unsignedInteger('pack_id')->nullable();
            $table->foreign('pack_id')->references('id')->on('packs')->onDelete('no action');
            $table->unsignedInteger('in_stock')->default(0);
            $table->unsignedInteger('in_shop')->default(0);
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('auction_price')->default(0);
            $table->boolean('in_auction')->default(false);
            $table->text('description')->default(null);
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
        Schema::dropIfExists('products');
    }
};
