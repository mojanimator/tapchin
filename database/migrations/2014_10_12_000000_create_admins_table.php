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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
//            $table->string('username', 50)->nullable();
            $table->string('fullname', 100);
            $table->string('email', 100)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->string('password', 200)->nullable();
            $table->json('access')->default(null);
            $table->enum('status', array_column(Variable::USER_STATUSES, 'name'))->default(array_column(Variable::USER_STATUSES, 'name')[0]);
            $table->integer('notifications')->unsigned()->default(0);
            $table->unsignedInteger('wallet')->default(0);
            $table->string('card', 16)->default(null)->nullable();
            $table->string('push_id', 20)->nullable();
            $table->timestamp('expires_at')->nullable()->default(null);
            $table->json('settings')->nullable()->default(null);
            $table->rememberToken();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('admins')->insert(\App\Http\Helpers\Variable::getAdmins());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
