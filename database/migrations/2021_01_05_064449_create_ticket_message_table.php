<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_message', function (Blueprint $table) {
            $table->increments('message_id');
            $table->integer('ticket_id')->nullable();
            $table->integer('osp_id')->nullable();
            $table->integer('isp_id')->nullable();
            $table->text('message')->nullable();
            $table->tinyInteger('unsend')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_message');
    }
}
