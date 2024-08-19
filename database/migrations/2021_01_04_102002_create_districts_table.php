<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('districts', function (Blueprint $table) {
        //     $table->increments('districts_id');
        //     $table->integer('regency_id')->nullable();
        //     $table->string('name',20)->nullable();
        // });
        DB::unprepared(file_get_contents(base_path('database/migrations/wilayah/districts.sql')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
