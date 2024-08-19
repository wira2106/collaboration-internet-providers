<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeValueJenisIdentitasOnPelangganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropColumn('jenis_identitas');
        });
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->enum('jenis_identitas', ['SIM', 'KTP'])->nullable()->after('status_kepemilikan');
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
            $table->dropColumn('jenis_identitas');
        });
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->enum('jenis_identitas', ['SIM', 'KTP']) - nullable()->after('status_kepemilikan');
        });
    }
}
