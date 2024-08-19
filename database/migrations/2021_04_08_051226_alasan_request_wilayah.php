<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlasanRequestWilayah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('alasan_request_wilayah');
        Schema::create('alasan_request_wilayah', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('request_wilayah_id')->default(null);
            $table->string('alasan');
            $table->enum('status', [
                'request',
                'confirmed',
                'accepted',
                'rejected',
                'disagree',
            ]);
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
        Schema::dropIfExists('alasan_request_wilayah');
    }
}
