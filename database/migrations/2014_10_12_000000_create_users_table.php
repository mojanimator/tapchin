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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
//            $table->string('username', 50)->nullable();
            $table->string('fullname', 100);
            $table->string('email', 100)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->string('password', 200)->nullable();
            $table->string('telegram_id', 50)->nullable();
            $table->string('eitaa_id', 50)->nullable();
            $table->string('bale_id', 50)->nullable();
            $table->string('soroush_id', 50)->nullable();
            $table->enum('role', \App\Http\Helpers\Variable::ROLES)->default(\App\Http\Helpers\Variable::ROLES[0]);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_block')->default(false);
            $table->boolean('wallet_active')->default(false);
            $table->integer('notifications')->unsigned()->default(0);
            $table->unsignedInteger('wallet')->default(0);
            $table->unsignedInteger('meta_wallet')->default(0);
            $table->string('card', 16)->default(null)->nullable();
            $table->string('ref_id', 10);
            $table->string('push_id', 20)->nullable();
            $table->timestamp('expires_at')->nullable()->default(null);
            $table->json('settings')->nullable()->default(null);
            $table->string('access', 20)->nullable()->default(null);
            $table->rememberToken();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('users')->insert(\App\Http\Helpers\Variable::getAdmins());
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
