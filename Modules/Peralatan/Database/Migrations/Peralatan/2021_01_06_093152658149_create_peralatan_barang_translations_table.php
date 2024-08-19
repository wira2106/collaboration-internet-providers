<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeralatanBarangTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peralatan__barang_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('barang_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['barang_id', 'locale']);
            $table->foreign('barang_id')->references('id')->on('peralatan__barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peralatan__barang_translations', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
        });
        Schema::dropIfExists('peralatan__barang_translations');
    }
}
