<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivePresalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_presales', function (Blueprint $table) {
            $table->increments('active_presale_id');
            $table->integer('osp_id')->nullable();
            $table->bigInteger('presale_id')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('active_presales');
    }
}
