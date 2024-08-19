<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMutasiSaldoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutasi_saldo', function (Blueprint $table) {
            $table->increments('mutasi_id');
            $table->integer('saldo_id')->nullable();
            $table->bigInteger('nominal')->nullable();
            $table->enum('tipe',['debit','credit'])->nullable();
            $table->dateTime('created_at')->nullable();
            $table->text('deskripsi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutasi_saldo');
    }
}
