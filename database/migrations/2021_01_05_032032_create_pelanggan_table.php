<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePelangganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->increments('pelanggan_id');
            $table->integer('presale_id')->nullable();
            $table->integer('isp_id')->nullable();
            $table->integer('paket_id')->nullable();
            $table->integer('biaya_mrc')->nullable();
            $table->integer('biaya_otc')->nullable();
            $table->string('nama_pelanggan',100)->nullable();
            $table->string('telepon',20)->nullable();
            $table->string('email',50)->nullable();
            $table->enum('status_kepemilikan',['pemilik','penyewa','lainnya'])->nullable();
            $table->enum('jenis_identitas',['SIM','KTP'])->nullable();
            $table->string('nomor_identitas',50)->nullable();
            $table->string('foto_identitas',20)->nullable();
            $table->enum('status',['so','survey','verifikasi','instalasi','aktivasi','aktif','cancel'])->nullable();
            $table->tinyInteger('suspended')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelanggan');
    }
}
