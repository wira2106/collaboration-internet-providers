<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSuspendedToSuspendedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pelanggan', function(Blueprint $table) {
            $table->dropColumn('suspended');
        });
        Schema::table('pelanggan', function(Blueprint $table) {
            $table->date('suspended_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pelanggan', function(Blueprint $table) {
            $table->dropColumn('suspended_at');
        });
        
        Schema::table('pelanggan', function(Blueprint $table) {
            $table->tinyInteger('suspended')->default(0)->change();
        });
    }
}
