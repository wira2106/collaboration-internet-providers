<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarangTambahanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_tambahan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_id')->nullable();
            $table->integer('barang_id')->nullable();
            $table->double('harga_per_pcs')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('harga')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang_tambahan');
    }
}
