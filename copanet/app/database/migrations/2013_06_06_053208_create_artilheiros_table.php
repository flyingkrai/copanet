<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArtilheirosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artilheiros', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 150);
            $table->string('foto')->nullable();
            $table->integer('time_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('artilheiros');
    }

}
