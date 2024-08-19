<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifEnumSettlement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice', function(Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('invoice', function(Blueprint $table) {
            $table->enum('status', ['pending', 'settlement'])->nullable()->after('periode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice', function(Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('invoice', function(Blueprint $table) {
            $table->enum('status', ['pending', 'setlement'])->default('pending');
        });
    }
}
