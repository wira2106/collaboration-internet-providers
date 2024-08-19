<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeralatanAlatTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peralatan__alat_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('alat_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['alat_id', 'locale']);
            $table->foreign('alat_id')->references('id')->on('peralatan__alats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peralatan__alat_translations', function (Blueprint $table) {
            $table->dropForeign(['alat_id']);
        });
        Schema::dropIfExists('peralatan__alat_translations');
    }
}
