<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('company_id');
            $table->string('name',100)->nullable();
            $table->enum('type',['isp','osp'])->nullable();
            $table->bigInteger('provinces_id')->nullable();
            $table->bigInteger('regencies_id')->nullable();
            $table->bigInteger('villages_id')->nullable();
            $table->bigInteger("districts_id")->nullable();
            $table->text('address')->nullable();
            $table->string('post_code',5)->nullable();
            $table->string('display_name',50)->nullable();
            $table->string('logo_perusahaan',30)->nullable();
            $table->double('pop_lat')->nullable();
            $table->double('pop_lon')->nullable();
            $table->double("company_lat")->default(0);
            $table->double("company_lon")->default(0);
            $table->text('pop_address');
            $table->integer('total_endpoint')->nullable();
            $table->integer('rating')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
