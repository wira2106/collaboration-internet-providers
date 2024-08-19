<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColomnToTarikSaldoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarik_saldo', function (Blueprint $table) {
            $table->integer('bank_account_id')->after('company_id')->nullable();
            $table->text('keterangan')->after('status')->nullable();
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
            $table->dropColumn('bank_account_id');
            $table->dropColumn('keterangan');
        });
    }
}
