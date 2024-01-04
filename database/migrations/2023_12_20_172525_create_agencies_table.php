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
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->json('access')->nullable();
            $table->enum('type', array_column(\App\Http\Helpers\Variable::AGENCY_TYPES, 'code'))->default(array_column(\App\Http\Helpers\Variable::AGENCY_TYPES, 'code')[count(\App\Http\Helpers\Variable::AGENCY_TYPES) - 1]);
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('admins')->onDelete('no action');
            $table->unsignedSmallInteger('province_id')->nullable();
            $table->foreign('province_id')->references('id')->on('cities')->onDelete('no action');
            $table->unsignedSmallInteger('county_id')->nullable();
            $table->foreign('county_id')->references('id')->on('cities')->onDelete('no action');
            $table->boolean('has_shop')->default(false);
            $table->string('address', 2048)->nullable();
            $table->string('location', 50)->nullable();
            $table->enum('status', array_column(Variable::STATUSES, 'name'))->default('inactive');

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
        Schema::dropIfExists('agencies');
    }
};
