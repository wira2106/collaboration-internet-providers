<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlasanSuspendPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::table('pelanggan', function(Blueprint $table) {
            $table->text('alasan_suspend')->nullable();
            $table->integer('suspend_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pelanggan', function(Blueprint $table) {
            $table->dropColumn('alasan_suspend');
            $table->dropColumn('suspend_id');
        });
    }
}
