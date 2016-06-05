<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xobject_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('term_id')->unsigned()->nullable();
            $table->float('price')->unsigned()->default(0);
            $table->string('photo',500);
            $table->timestamps();

            $table->foreign('xobject_id')
                ->references('id')
                ->on('xobjects')
                ->onDelete('cascade');

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('cascade');

            $table->foreign('term_id')
                ->references('id')
                ->on('terms')
                ->onDelete('cascade');

            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
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
        Schema::drop('products');
    }
}
