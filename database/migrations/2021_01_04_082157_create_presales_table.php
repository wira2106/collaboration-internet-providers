<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presales', function (Blueprint $table) {
            $table->increments('presale_id');
            $table->string('site_id',100)->nullable();
            $table->integer('wilayah_id')->nullable();
            $table->integer('end_point_id')->nullable();
            $table->integer('class_id')->nullable();
            $table->double('panjang_kabel')->nullable();
            $table->double('lat')->nullable();
            $table->double('lon')->nullable();
            $table->bigInteger('provinces_id')->nullable();
            $table->bigInteger('districts_id')->nullable();
            $table->bigInteger('regencies_id')->nullable();
            $table->bigInteger('villages_id')->nullable();
            $table->text('address')->nullable();
            $table->string('foto_rumah',30)->nullable();
            $table->string('nama_gang',50)->nullable();
            $table->string('no_rumah',10)->nullable();
            $table->string('kode_pos',20)->nullable();
            $table->double('biaya_kabel')->nullable();
            $table->integer('status_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('biaya_instalasi')->nullable();
            $table->integer('total_biaya')->nullable();
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
        Schema::dropIfExists('presales');
    }
}
