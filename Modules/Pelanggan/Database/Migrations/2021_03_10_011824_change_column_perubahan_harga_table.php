<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnPerubahanHargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('perubahan_harga', function (Blueprint $table) {
        //     $table->renameColumn('pelanggan_id', 'perubahan_harga_id');
        // });
        Schema::table('perubahan_harga', function (Blueprint $table) {
            $table->dropColumn('pelanggan_id');
        });
        Schema::table('perubahan_harga', function (Blueprint $table) {
            $table->increments('perubahan_harga_id')->first();
            $table->integer('pelanggan_id')->after('perubahan_harga_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perubahan_harga', function (Blueprint $table) {
            $table->dropColumn('perubahan_harga_id');
        });
    }
}
