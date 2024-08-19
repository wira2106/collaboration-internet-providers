<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedAtOnTanggalPengajuanInstalasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tanggal_pengajuan_instalasi', function(Blueprint $table) {
            $table->timestamp('created_at')->useCurrent();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tanggal_pengajuan_instalasi', function(Blueprint $table) {
            $table->dropColumn('created_at');
         });
    }
}
