<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestWilayahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_wilayah', function (Blueprint $table) {
            $table->increments('request_wilayah_id');
            $table->integer('isp_id')->nullable();
            $table->integer('osp_id')->nullable();
            $table->integer('wilayah_id')->nullable();
            $table->enum('status',['request','confirmed','rejected','accepted'])->nullable();
            $table->text('alasan')->nullable();
            $table->dateTime('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_wilayah');
    }
}
