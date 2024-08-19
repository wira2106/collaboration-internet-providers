<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BiayaKabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('biaya_kabel', function (Blueprint $table) {
            $table->increments('biaya_kabel_id');
            $table->integer('company_id');
            $table->enum('tipe',['dropcore','precone'])->nullable();
            $table->integer('harga_per_meter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('biaya_kabel');
    }
}
