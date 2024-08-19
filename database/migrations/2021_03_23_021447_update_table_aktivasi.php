<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableAktivasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aktivasi', function(Blueprint $table) {
            $table->dropColumn('noc');
            $table->enum('status',['proses','selesai','cancel'])->nullable();            
        });

        Schema::create('noc_aktivasi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aktivasi_id');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aktivasi', function(Blueprint $table) {
            $table->integer('noc');
            $table->dropColumn('status');
        });

        Schema::dropIfExists('noc_aktivasi');
    }
}
