<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVillagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('villages', function (Blueprint $table) {
        //      $table->increments('village_id');
        //     $table->integer('districts_id')->nullable();
        //     $table->string('name',20)->nullable();
        // });
        DB::unprepared(file_get_contents(base_path('database/migrations/wilayah/villages.sql')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('villages');
    }
}
