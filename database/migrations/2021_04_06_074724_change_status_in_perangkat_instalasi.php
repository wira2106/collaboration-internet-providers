<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusInPerangkatInstalasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perangkat_instalasi', function(Blueprint $table) {
            $table->enum('status',['Dipinjamkan','Dibeli']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perangkat_instalasi', function(Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
