<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewStatusOnAktivasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aktivasi', function(Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('aktivasi', function(Blueprint $table) {
            $table->enum('status',['proses','selesai','approve','cancel']);
            $table->text('keterangan_unapprove')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aktivasi', function(Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('aktivasi', function(Blueprint $table) {
            $table->enum('status',['proses','selesai','cancel']);
            $table->dropColumn('keterangan_unapprove');
        });
    }
}
