<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMomentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content')->commit('Moment Content');
            $table->unsignedInteger('auth_id')->commit('OAuthID');
            $table->tinyInteger('anonymous')->default(0);
            $table->unsignedInteger('warehouse_id');
            $table->tinyInteger('top')->default(0);
            $table->string('notify_id')->nullable();
            $table->tinyInteger('type')->commit('Moment Type');
            $table->tinyInteger('state')->default(0)->commit('Moment State');
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
        Schema::dropIfExists('moments');
    }
}
