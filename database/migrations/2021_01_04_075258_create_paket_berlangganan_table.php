<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaketBerlanggananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paket_berlangganan', function (Blueprint $table) {
            $table->increments('paket_id');
            $table->integer('company_id')->nullabel();
            $table->string('nama_paket',100)->nullabel();
            $table->integer('biaya_otc')->nullabel();
            $table->integer('harga_paket')->nullabel();
            $table->double('sla')->nullabel();
            $table->dateTime('created_at')->nullabel();
            $table->integer('created_by')->nullabel();
            $table->dateTime('updated_at')->nullabel();
            $table->integer('updated_by')->nullabel();
            $table->dateTime('deleted_at')->nullabel();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paket_berlangganan');
    }
}
