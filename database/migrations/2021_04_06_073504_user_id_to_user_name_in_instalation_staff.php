<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserIdToUserNameInInstalationStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instalation_staff', function(Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('instalation_staff', function(Blueprint $table) {
            $table->String('user_name',225);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instalation_staff', function(Blueprint $table) {
            $table->dropColumn('user_name');
        });
    }
}
