<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('provinces', function (Blueprint $table) {
        //     $table->increments('province_id');
        //     $table->string('name')->nullable();
        // });
        DB::unprepared(file_get_contents(base_path('database/migrations/wilayah/provinces.sql')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provinces');
    }
}
