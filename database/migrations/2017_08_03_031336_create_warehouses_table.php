<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('app_id');
            $table->string('template_id');
            $table->string('secret');
            $table->string('api_key');
            $table->unsignedInteger('user_id');
            $table->string('sslCert')->nullable();
            $table->string('sslKey')->nullable();
            $table->string('caInfo')->nullable();
            $table->string('m_id',100)->nullable();
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
        Schema::dropIfExists('warehouses');
    }
}
