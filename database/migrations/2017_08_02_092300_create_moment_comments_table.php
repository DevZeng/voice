<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMomentCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moment_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('moment_id');
//            $table->unsignedInteger('comment_id')->default(0);
//            $table->unsignedInteger('base_comment_id')->default(0);
//            $table->unsignedInteger('reply_auth_id')->default(0);
            $table->string('content');
            $table->integer('like')->default(0);
            $table->unsignedInteger('auth_id');
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
        Schema::dropIfExists('moment_comments');
    }
}
