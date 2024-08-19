<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnUserIdInSurveyStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_staff', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('survey_staff', function (Blueprint $table) {
            $table->string('nama_teknisi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_staff', function (Blueprint $table) {
            $table->dropColumn('nama_teknisi');
        });
        Schema::table('survey_staff', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
        });
    }
}
