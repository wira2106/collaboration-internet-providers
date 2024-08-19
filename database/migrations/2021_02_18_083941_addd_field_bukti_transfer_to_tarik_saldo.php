<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdddFieldBuktiTransferToTarikSaldo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarik_saldo', function (Blueprint $table) {
            $table->string('bukti_transfer')->after('amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('tarik_saldo', function (Blueprint $table) {
            $table->dropColumn('bukti_transfer');
        });
    }
}
