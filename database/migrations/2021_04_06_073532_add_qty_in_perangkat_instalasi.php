<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQtyInPerangkatInstalasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perangkat_instalasi', function(Blueprint $table) {
            $table->integer('qty')->after('jenis_perangkat');
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
            $table->dropColumn('qty');
        });
    }
}
