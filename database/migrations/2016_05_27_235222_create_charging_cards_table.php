<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargingCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charging_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('cvv');
            $table->boolean('is_used')->default(false);
            $table->float('price')->unsigned()->default(0);
            $table->integer('value')->unsigned()->default(0);
            $table->timestamp('expiry_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('charging_cards');
    }
}
