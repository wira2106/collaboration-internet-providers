<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTicketSupportSuspend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_support_suspend', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id')->nullable();
            $table->integer('pelanggan_id')->nullable();
            $table->dateTime('tgl_suspend')->nullable();
            $table->text('alasan')->nullable();
            $table->enum('status',['accept','rejected'])->nullable();
            $table->integer('accept_osp_by')->nullable();
            $table->dateTime('tgl_acc_osp')->nullable();
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