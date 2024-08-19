<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_timer', function (Blueprint $table) {
            $table->increments('activity_id')->primarykey();
            $table->integer('company_id')->nullable();
            $table->integer('pelanggan_id')->nullable();
            $table->integer('activity_tipe_id')->nullable();
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->bigInteger('total_waktu')->nullable();
            $table->integer('created_by')->nullable();
        });

        Schema::create('activity_tipe', function (Blueprint $table) {
            $table->increments('activity_tipe_id')->primarykey();
            $table->string('nama_activity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_timer');
        Schema::dropIfExists('activity_tipe');
    }
}
