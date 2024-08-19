<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHariLiburTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hari_libur', function (Blueprint $table) {
            $table->increments('hari_libur_id');
            $table->integer('company_id')->nullable;
            $table->date('tgl_libur')->nullable;
            $table->text('deskripsi_libur')->nullable;
            $table->dateTime('created_at')->nullable;
            $table->integer('created_by')->nullable;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hari_libur');
    }
}
