<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFileKontrakToRequestWilayahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kontrak_wilayah', function (Blueprint $table) {
            $table->string('file_kontrak')->after('request_wilayah_id')->nullable();
            $table->integer('toleransi_tunggakan')->after('file_kontrak')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kontrak_wilayah', function (Blueprint $table) {
            $table->dropColumn('file_kontrak');
            $table->dropColumn('toleransi_tunggakan');
        });
    }
}
