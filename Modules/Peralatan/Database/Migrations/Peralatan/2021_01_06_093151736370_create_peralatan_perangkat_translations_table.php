<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeralatanPerangkatTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peralatan__perangkat_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('perangkat_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['perangkat_id', 'locale']);
            $table->foreign('perangkat_id')->references('id')->on('peralatan__perangkats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peralatan__perangkat_translations', function (Blueprint $table) {
            $table->dropForeign(['perangkat_id']);
        });
        Schema::dropIfExists('peralatan__perangkat_translations');
    }
}
