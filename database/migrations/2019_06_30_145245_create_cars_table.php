<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->foreign('vendor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('make');
            $table->string('model');
            $table->string('color');
            $table->text('description', 10);
            $table->string('condition', 3);
            $table->integer('price');
            $table->string('picture');
            $table->boolean('purchased')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
