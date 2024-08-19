<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerubahanHargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perubahan_harga', function (Blueprint $table) {
            $table->increments('pelanggan_id');
            $table->integer('harga_otc')->nullable();
            $table->integer('harga_mrc')->nullable();
            $table->enum('status',['request','accepted','rejected'])->nullable();
            $table->text('keterangan')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perubahan_harga');
    }
}
