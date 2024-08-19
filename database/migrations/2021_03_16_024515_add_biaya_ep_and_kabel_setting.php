<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBiayaEpAndKabelSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting', function(Blueprint $table) {
            $table->integer('biaya_ep');
            $table->integer('kabel_per_meter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting', function(Blueprint $table) {
            $table->dropColumn('biaya_ep');
            $table->dropColumn('kabel_per_meter');
        });
    }
}
