<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_type');
            $table->bigInteger('module_id')->unsigned();
            $table->bigInteger('room_id')->unsigned();
            $table->boolean('is_dimable')->default(false);
            $table->integer('State')->nullable();
            $table->integer('Night_start_state')->nullable();
            $table->integer('Night_end_state')->nullable();
            $table->foreign('module_id')->references('id')->on('modules');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->timestamps();
            $table->integer('used_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
