<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLengthDokumentasiDetailPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggan', function(Blueprint $table) {
            $table->dropColumn('foto_identitas');
        });
        Schema::table('pelanggan', function(Blueprint $table) {
            $table->String('foto_identitas',225)->after('nomor_identitas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pelanggan', function(Blueprint $table) {
            $table->dropColumn('foto_identitas');
        });
    }
}
