<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnQtyOnPerangkatTambahanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perangkat_tambahan', function (Blueprint $table) {
            $table->integer('qty')->nullable()->after('perangkat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perangkat_tambahan', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
}
