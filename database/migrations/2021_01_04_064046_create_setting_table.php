<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_fee')->nullable();
            $table->double('persentase_otc_mrc')->nullable();
            $table->double('persentase_refund_osp')->nullable();
            $table->double('persentase_refund_openaccess')->nullable();
            $table->integer('sla_odp')->nullable();
            $table->integer('sla_join_box')->nullable();
            $table->integer('sla_fixing_stack')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting');
    }
}
