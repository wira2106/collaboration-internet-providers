<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAcceptedOnInRequestWilayahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_wilayah', function (Blueprint $table) {
            $table->dateTime('accepted_at')->nullable()->after('alasan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_wilayah', function (Blueprint $table) {
            $table->dropColumn('accepted_at');
        });
    }
}
