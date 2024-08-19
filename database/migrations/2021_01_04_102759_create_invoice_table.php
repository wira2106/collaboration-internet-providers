<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->bigInteger('invoice_id')->autoIncrement();
            $table->integer('isp_id')->nullable();
            $table->integer('osp_id')->nullable();
            $table->date('periode')->nullable();
            $table->enum('status',['pending','setlement'])->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('settlement_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice');
    }
}
