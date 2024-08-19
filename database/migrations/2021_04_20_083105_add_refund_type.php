<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefundType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund_item', function(Blueprint $table) {
            $table->dropColumn('refund_type');
        }); 
        Schema::table('refund_item', function(Blueprint $table) {
            $table->enum('refund_type',['sla', 'suspend', 'prorata']);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refund_item', function(Blueprint $table) {
            $table->dropColumn('refund_type');
        }); 
        Schema::table('refund_item', function(Blueprint $table) {
            $table->enum('refund_type',['sla', 'suspend']);
        }); 
    }
}
