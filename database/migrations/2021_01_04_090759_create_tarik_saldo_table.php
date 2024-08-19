<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarikSaldoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarik_saldo', function (Blueprint $table) {
            $table->increments('tarik_saldo_id');
            $table->integer('company_id')->nullable();
            $table->integer('amount')->nullable();
            $table->enum('status',['pending','success','expired','cancel'])->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('success_on')->nullable();
            $table->integer('success_by')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarik_saldo');
    }
}
