<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldIntoInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice', function(Blueprint $table) {
            $table->integer('amount')->default(0);
            $table->integer('nominal_ppn')->default(0);
            $table->integer('nominal_dpp')->default(0);
            $table->string('title')->nullable();
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
            $table->dropColumn('amount');
            $table->dropColumn('nominal_ppn');
            $table->dropColumn('nominal_dpp');
            $table->dropColumn('title');
        });
    }
}
