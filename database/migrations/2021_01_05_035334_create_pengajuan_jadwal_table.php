<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengajuanJadwalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_jadwal', function (Blueprint $table) {
            $table->increments('pengajuan_id');
            $table->integer('pelanggan_id')->nullable();
            $table->string('keterangan')->nullable();
            $table->dateTime('tgl_rekomendasi')->nullable();
            $table->enum('type',['instalasi','survey'])->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->enum('status',['pending','accept','reschedule'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_jadwal');
    }
}
