<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RevisiTableInstalasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instalasi', function(Blueprint $table) {
            $table->dropColumn('tgl_instalasi');            
        });
        Schema::table('instalasi', function(Blueprint $table) {
            $table->date('tgl_instalasi')->nullable();
            $table->integer('slot_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instalasi', function(Blueprint $table) {
            $table->dropColumn('tgl_instalasi');
            $table->dropColumn('slot_id');
        });
        Schema::table('instalasi', function(Blueprint $table) {
            $table->dateTime('tgl_instalasi')->nullable();
        });
    }
}
