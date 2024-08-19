<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalyticAnalyticTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytic__analytic_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('analytic_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['analytic_id', 'locale']);
            $table->foreign('analytic_id')->references('id')->on('analytic__analytics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analytic__analytic_translations', function (Blueprint $table) {
            $table->dropForeign(['analytic_id']);
        });
        Schema::dropIfExists('analytic__analytic_translations');
    }
}
