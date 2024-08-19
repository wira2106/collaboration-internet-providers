<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnModeAndBrivanoTopupSaldo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topup_saldo', function (Blueprint $table) {
            $table->enum('mode',['manual','briva'])->after('topup_id')->default('manual')->nullable();
            $table->string('brivaNo')->nullable();
            $table->string('custCode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
