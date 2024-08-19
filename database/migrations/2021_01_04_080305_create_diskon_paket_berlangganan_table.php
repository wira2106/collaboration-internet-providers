<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiskonPaketBerlanggananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diskon_paket_berlangganan', function (Blueprint $table) {
            $table->increments('biaya_id');
            $table->integer('paket_id')->nullable();
            $table->double('diskon')->nullable();
            $table->integer('start_pelanggan')->nullable();
            $table->integer('end_pelanggan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diskon_paket_berlangganan');
    }
}
