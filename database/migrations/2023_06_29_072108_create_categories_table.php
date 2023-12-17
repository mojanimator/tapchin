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
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->boolean('is_active')->default(true);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->smallInteger('type')->unsigned()->nullable();
            $table->timestamps();
            $table->unique(array('name', 'type'));
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('no action');
        });

        \Illuminate\Support\Facades\DB::table('categories')->insert(Variable::CATEGORIES);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
