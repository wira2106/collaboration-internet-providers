<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlotInstalasiOnPengajuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tanggal_pengajuan_instalasi', function(Blueprint $table) {
            $table->dropColumn('jam_instalasi');
            $table->integer('slot_id');
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
            $table->time('jam_instalasi');
            $table->dropColumn('slot_id');
        });
    }
}
