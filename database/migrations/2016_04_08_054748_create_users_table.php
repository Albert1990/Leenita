<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id')->unsigned();
            $table->string('name',500);
            $table->string('mobile_number')->unique();
            $table->string('email',500);
            $table->string('password',500);
            $table->integer('points')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->float('location_x')->default(0);
            $table->float('location_y')->default(0);
            $table->integer('notifications_count')->unsigned()->default(0);
            $table->float('rank')->unsigned()->default(0);
            $table->string('facebook_token',500);
            $table->string('imei');
            $table->string('photo',500);
            $table->integer('language_id')->unsigned()->nullable();
            $table->timestamps();


            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');

            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
