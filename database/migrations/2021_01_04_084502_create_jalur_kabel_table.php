<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJalurKabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jalur_kabel', function (Blueprint $table) {
            $table->increments('jalur_kabel_id');
            $table->bigInteger('presale_id')->nullable();
            $table->double('lat')->nullable();
            $table->double('lon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jalur_kabel');
    }
}
