<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOAuthUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('o_auth_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('warehouse_id');
            $table->string('open_id')->unique()->commit('Wechat Unique ID');
            $table->string('nickname',200)->commit('Wechatnickname');
            $table->tinyInteger('gender')->commit('Gender');
            $table->string('city',100)->commit('City');
            $table->integer('integral')->default(0);
            $table->string('province',100)->commit('Province');
            $table->string('avatarUrl',300)->commit('wechatAvatarUrl');
            $table->integer('birthday')->nullable()->commit('userBirthday');
            $table->string('number',20)->nullable()->commit('userNumber');
            $table->tinyInteger('ban')->default(0);
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
        Schema::dropIfExists('o_auth_users');
    }
}
