<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTicketSupportSla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_support_sla', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id')->nullable();
            $table->integer('pelanggan_id')->nullable();
            $table->dateTime('start_gangguan')->nullable();
            $table->dateTime('end_gangguan')->nullable();
            $table->integer('accept_osp_by')->nullable();
            $table->dateTime('tgl_acc_osp')->nullable();
            $table->integer('accept_isp_by')->nullable();
            $table->dateTime('tgl_acc_isp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_support_sla');
    }
}