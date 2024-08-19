<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEndPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('end_point', function (Blueprint $table) {
            $table->increments('end_point_id');
            $table->integer('wilayah_id')->nullable();
            $table->string('nama_end_point',50)->nullable();
            $table->enum('tipe',['Fixing Slack','JB','ODP'])->nullable();
            $table->double('lat_end_point')->nullable();
            $table->double('lon_end_point')->nullable();
            $table->text('address')->nullable();
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
        Schema::dropIfExists('end_point');
    }
}
