<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBiayaKabelRangeBiayaKabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('range_biaya_kabel', function(Blueprint $table) {
            $table->integer('biaya_kabel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('range_biaya_kabel', function (Blueprint $table) {
            $table->dropColumn('biaya_kabel_id');
        });
    }
}
