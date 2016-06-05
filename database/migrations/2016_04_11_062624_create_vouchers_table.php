<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('xobject_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->integer('term_id')->unsigned();
            $table->tinyInteger('pricing_type');
            $table->integer('value');
            $table->integer('quantity');
            $table->timestamp('expiry_date');
            $table->boolean('is_active')->default(true);
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vouchers');
    }
}
