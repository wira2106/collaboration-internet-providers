<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRangeBiayaKabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_biaya_kabel', function (Blueprint $table) {
             $table->increments('range_id');
            $table->integer('company_id')->references('company_id')->on('companies');
            $table->integer('panjang_kabel')->nullable();
            $table->integer('biaya')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('range_biaya_kabel');
    }
}
