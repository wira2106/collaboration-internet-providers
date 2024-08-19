<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWilayahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wilayah', function (Blueprint $table) {
            $table->increments('wilayah_id');
            $table->integer('company_id')->nullable();
            $table->string('name',50)->nullable();
            $table->bigInteger('provinces_id')->nullable();
            $table->bigInteger('regencies_id')->nullable();
            $table->bigInteger('districts_id')->nullable();
            $table->bigInteger('villages_id')->nullable();            
            $table->string('post_code',10)->nullable();
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
        Schema::dropIfExists('wilayah');
    }
}
