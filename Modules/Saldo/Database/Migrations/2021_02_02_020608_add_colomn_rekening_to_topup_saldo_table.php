<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColomnRekeningToTopupSaldoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topup_saldo', function (Blueprint $table) {
            $table->string('rekening_pengirim')->after('amount')->nullable();
            $table->string('atas_nama')->after('rekening_pengirim')->nullable();
            $table->integer('bank_id')->after('company_id')->nullable();
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
            $table->dropColumn('rekening_pengirim');
            $table->dropColumn('atas_nama');
            $table->dropColumn('bank_account_id');
        });
    }
}
