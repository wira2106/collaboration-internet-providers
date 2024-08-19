<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIjinUsahaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ijin_usaha', function (Blueprint $table) {
            $table->increments('ijin_usaha_id');
            $table->integer('company_id')->references('company_id')->on('companies');
            $table->string('name',50)->nullable();
            $table->string('filename',50)->nullable();
            $table->date('expired_date')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ijin_usaha');
    }
}
