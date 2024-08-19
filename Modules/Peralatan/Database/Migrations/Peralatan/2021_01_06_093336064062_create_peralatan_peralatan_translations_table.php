<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeralatanPeralatanTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peralatan__peralatan_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('peralatan_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['peralatan_id', 'locale']);
            $table->foreign('peralatan_id')->references('id')->on('peralatan__peralatans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peralatan__peralatan_translations', function (Blueprint $table) {
            $table->dropForeign(['peralatan_id']);
        });
        Schema::dropIfExists('peralatan__peralatan_translations');
    }
}
