<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTableSuspend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('suspend');

        Schema::create('suspend', function (Blueprint $table) {
            $table->bigIncrements('suspend_id');
            $table->integer('pelanggan_id')->nullable();
            $table->text('alasan')->nullable();
            $table->date('tgl_suspend')->nullable();
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
        Schema::dropIfExists('suspend');
    }
}