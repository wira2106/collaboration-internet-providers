<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('regencies', function (Blueprint $table) {
        //     $table->increments('regency_id');
        //     $table->integer('province_id')->nullable();
        //     $table->string('name',20)->nullable();
        // });
        DB::unprepared(file_get_contents(base_path('database/migrations/wilayah/regencies.sql')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regencies');
    }
}
