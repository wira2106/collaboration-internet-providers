<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pelanggan_id')->nullable();
            $table->dateTime('tgl_survey')->nullable();
            $table->integer('tinggi_bangunan')->nullable();
            $table->enum('satuan_tinggi',['meter','lantai'])->nullable();
            $table->text('foto_jalur_kabel')->nullable();
            $table->text('foto_signal_kabel')->nullable();
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
        Schema::dropIfExists('survey');
    }
}
