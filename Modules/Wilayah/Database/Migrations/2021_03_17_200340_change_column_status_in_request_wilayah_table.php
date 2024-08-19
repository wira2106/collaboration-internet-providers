<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnStatusInRequestWilayahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_wilayah', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('request_wilayah', function (Blueprint $table) {
            $table->enum('status', ['request', 'confirmed', 'rejected', 'disagree', 'accepted'])->default('request')->after('wilayah_id');
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
            $table->dropColumn('status');
        });
    }
}
