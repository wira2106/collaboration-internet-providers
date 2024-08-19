<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresaleClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presale_class', function (Blueprint $table) {
            $table->increments('class_id');
            $table->string('class_name')->nullable();
            $table->string('icon')->nullable();
            $table->integer('deleted')->default(0);
        });

        Schema::create('status_presales', function (Blueprint $table) {
            $table->bigIncrements("status_id");
            $table->string("name");
            $table->tinyInteger("deleted")->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presale_class');
        Schema::dropIfExists('status_presales');
    }
}
