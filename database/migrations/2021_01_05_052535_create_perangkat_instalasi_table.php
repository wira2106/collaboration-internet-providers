<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerangkatInstalasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perangkat_instalasi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('instalasi_id')->nullable();
            $table->integer('perangkat_id')->nullable();
            $table->enum('jenis_perangkat',['ONT','akses point'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perangkat_instalasi');
    }
}
