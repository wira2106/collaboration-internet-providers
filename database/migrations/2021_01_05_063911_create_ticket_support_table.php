<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketSupportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_support', function (Blueprint $table) {
            $table->increments('ticket_id');
            $table->integer('pelanggan_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->dateTime('start_gangguan')->nullable();
            $table->dateTime('end_gangguan')->nullable();
            $table->integer('accept_osp_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->integer('closed_by')->nullable();
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
        Schema::dropIfExists('ticket_support');
    }
}
