<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_log', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('device_id')->unsigned();
            $table->foreign('device_id')->references('id')->on('devices');
            $table->timestamps();
            $table->integer('controller');
            $table->integer('State');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('state_log');
    }
}
