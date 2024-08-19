<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableTicketSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_support', function(Blueprint $table) {
             $table->integer('accept_isp_by')->nullable();
             $table->datetime('tgl_acc_isp')->nullable();
             $table->datetime('tgl_acc_osp')->nullable();
             $table->string('subject')->nullable();
             $table->string('code');
             $table->enum('status',['open',"open","closed","reply_admin","reply_isp","reply_osp"])->default("open");
             $table->dropColumn('keterangan');
        });

        Schema::table('ticket_message', function(Blueprint $table) {
            $table->text('attachments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_support', function(Blueprint $table) {
             $table->dropColumn('accept_isp_by');
             $table->dropColumn('tgl_acc_isp');
             $table->dropColumn('tgl_acc_osp');
             $table->dropColumn('subject');
             $table->dropColumn('code');
             $table->dropColumn('status');
             $table->text('keterangan')->nullable();
        });

        Schema::table('ticket_message', function(Blueprint $table) {
            $table->dropColumn('attachments');
        });
    }
}
