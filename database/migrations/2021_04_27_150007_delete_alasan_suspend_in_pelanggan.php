<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteAlasanSuspendInPelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn('alasan_suspend');
            $table->dropColumn('suspended_at');
        });

        Schema::table('pelanggan', function (Blueprint $table) {
            $table->String('kode_pelanggan',50)->after('pelanggan_id')->nullable();
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
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn('kode_pelanggan');
            $table->dropColumn('suspend_id');
        });
    }
}