<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstalasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instalasi', function (Blueprint $table) {
            $table->increments('instalasi_id');
            $table->integer('pelanggan_id')->nullable();
            $table->dateTime('tgl_instalasi')->nullable();
            $table->time('jam_start')->nullable();
            $table->time('jam_stop')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status',['active','finish','reschedule','cancel'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instalasi');
    }
}
