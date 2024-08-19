<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTableTicketSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_support', function (Blueprint $table) {
            $table->dropColumn('pelanggan_id');
            $table->dropColumn('start_gangguan');
            $table->dropColumn('end_gangguan');
            $table->dropColumn('accept_osp_by');
            $table->dropColumn('accept_isp_by');
        });

        Schema::table('ticket_support', function (Blueprint $table) {
            $table->enum('type',['sla','suspend'])->after('ticket_id')->nullable;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_support', function (Blueprint $table) {
            $table->dropColumn('type');
        });

    }
}