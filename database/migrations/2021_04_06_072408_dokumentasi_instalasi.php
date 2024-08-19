<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DokumentasiInstalasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumentasi_instalasi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('instalasi_id');
            $table->String('foto_dokumentasi',150);
            $table->String('keterangan',150);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dukumentasi_instalasi');
    }
}