<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTypeEndPoinOnPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggan', function(Blueprint $table) {
           $table->enum("tipe_ep",["Fixing Slack","JB","ODP"])->default("ODP");
           $table->bigInteger("estimasi_instalasi");
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
            $table->dropColumn("tipe_ep");
            $table->dropColumn("estimasi_instalasi");
         });
    }
}
