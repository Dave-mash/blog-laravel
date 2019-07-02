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
            $table->increments('id');
            $table->integer('vendor_id')->unsigned()->nullable();
            $table->string('make');
            $table->string('model');
            $table->string('color');
            $table->text('description', 10);
            $table->string('condition', 3);
            $table->integer('price');
            $table->string('picture')->default('default.jpg');
            $table->boolean('purchased')->default(false);
            $table->timestamps();
            
            $table->foreign('vendor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('cars');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
