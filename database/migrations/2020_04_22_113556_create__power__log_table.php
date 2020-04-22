<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePowerLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('power_log', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('date');
            $table->integer('hr_0')->nullable();
            $table->integer('hr_1')->nullable();
            $table->integer('hr_2')->nullable();
            $table->integer('hr_3')->nullable();
            $table->integer('hr_4')->nullable();
            $table->integer('hr_5')->nullable();
            $table->integer('hr_6')->nullable();
            $table->integer('hr_7')->nullable();
            $table->integer('hr_8')->nullable();
            $table->integer('hr_9')->nullable();
            $table->integer('hr_10')->nullable();
            $table->integer('hr_11')->nullable();
            $table->integer('hr_12')->nullable();
            $table->integer('hr_13')->nullable();
            $table->integer('hr_14')->nullable();
            $table->integer('hr_15')->nullable();
            $table->integer('hr_16')->nullable();
            $table->integer('hr_17')->nullable();
            $table->integer('hr_18')->nullable();
            $table->integer('hr_19')->nullable();
            $table->integer('hr_20')->nullable();
            $table->integer('hr_21')->nullable();
            $table->integer('hr_22')->nullable();
            $table->integer('hr_23')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('power_log');
    }
}
