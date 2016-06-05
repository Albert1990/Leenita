<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso2',2);
            $table->string('short_name',80);
            $table->string('long_name',80);
            $table->string('iso3',3);
            $table->string('numcode',6);
            $table->string('un_member',12);
            $table->string('calling_code',8);
            $table->string('cctld',5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('countries');
    }
}
