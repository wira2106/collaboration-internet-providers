<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSaldoMengendapOnPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         //
        Schema::create('saldo_biaya_pelanggan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pelanggan_id');
            $table->integer('total_biaya');
            $table->integer('saldo_mengendap');
            $table->integer('biaya_admin');
            $table->double('persentase_biaya_admin');
            $table->integer('biaya_osp');
            $table->double('persentase_biaya_osp');
            $table->integer('pengembalian_isp');
            $table->tinyInteger('settlement')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saldo_biaya_pelanggan');
    }
}
