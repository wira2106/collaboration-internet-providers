<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryPelangganSlaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_pelanggan_sla', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pelanggan_id')->nullable();
            $table->integer('paket_id')->nullable();
            $table->integer('biaya_mrc')->nullable();
            $table->integer('sla_paket')->nullable();
            $table->integer('total_koneksi_mati')->nullable();
            $table->double('persentase_total_koneksi_mati')->nullable();
            $table->double('persentase_toleransi')->nullable();
            $table->integer('total_pengurangan_tagihan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_pelanggan_sla');
    }
}
