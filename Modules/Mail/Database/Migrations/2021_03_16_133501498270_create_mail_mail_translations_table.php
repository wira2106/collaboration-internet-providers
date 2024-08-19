<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailMailTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail__mail_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Your translatable fields

            $table->integer('mail_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['mail_id', 'locale']);
            $table->foreign('mail_id')->references('id')->on('mail__mails')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mail__mail_translations', function (Blueprint $table) {
            $table->dropForeign(['mail_id']);
        });
        Schema::dropIfExists('mail__mail_translations');
    }
}
