<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFieldAlatInstalasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alat_instalasi', function(Blueprint $table) {
            $table->integer('qty');
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
        Schema::table('alat_instalasi', function(Blueprint $table) {
            $table->dropColumn('qty');
            $table->dropColumn('status');
        });
    }
}
