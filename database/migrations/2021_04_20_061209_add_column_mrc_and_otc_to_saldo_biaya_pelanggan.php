<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMrcAndOtcToSaldoBiayaPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saldo_biaya_pelanggan', function(Blueprint $table) {
            $table->integer('mrc')->default(0);
            $table->integer('otc')->default(0);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saldo_biaya_pelanggan', function(Blueprint $table) {
            $table->dropColumn('mrc');
            $table->dropColumn('otc');
        });
    }
}
