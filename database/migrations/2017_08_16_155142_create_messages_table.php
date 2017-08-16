<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('auth_id')->default(0);
            $table->string('content');
            $table->unsignedInteger('moment_id');
            $table->unsignedInteger('receive_id');
            $table->unsignedInteger('reply_id')->default(0);
            $table->unsignedInteger('comment_id')->default(0);
            $table->tinyInteger('read')->default(0);
            $table->tinyInteger('type')->default(1);
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
        Schema::dropIfExists('messages');
    }
}
