<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_item', function (Blueprint $table) {
            $table->bigInteger('refund_id')->autoIncrement();
            $table->bigInteger('invoice_item_id');
            $table->integer('refund_amount')->nullable();
            $table->enum('refund_type',['sla','suspend'])->nullable();
            $table->text('description')->nullable();
            $table->dateTime('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_item');
    }
}
