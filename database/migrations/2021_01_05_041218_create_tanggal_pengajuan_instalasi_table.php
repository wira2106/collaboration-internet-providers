<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTanggalPengajuanInstalasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tanggal_pengajuan_instalasi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pengajuan_id')->nullable();
            $table->date('tgl_instalasi')->nullable();
            $table->time('jam_instalasi')->nullable();
            $table->enum('status',['active','not_active'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tanggal_pengajuan_instalasi');
    }
}
