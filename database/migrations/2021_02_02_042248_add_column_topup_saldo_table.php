<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTopupSaldoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topup_saldo', function (Blueprint $table) {
            $table->string('bukti_transfer')->after('amount')->nullable();
            $table->date('tgl_transfer')->after('status')->nullable();
            $table->integer('bank_account_id')->after('company_id')->nullable();
            $table->text('keterangan')->after('tgl_transfer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('topup_saldo', function (Blueprint $table) {
            $table->dropColumn('bukti_transfer');
            $table->dropColumn('tgl_transfer');
            $table->dropColumn('bank_account_id');
            $table->dropColumn('keterangan');
        });
    }
}
