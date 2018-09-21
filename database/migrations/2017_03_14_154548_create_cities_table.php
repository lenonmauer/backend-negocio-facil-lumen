<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCitiesTable extends Migration
{
  public function up()
  {
    Schema::create('cities', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('state_id')->unsigned();
      $table->string('name', 64);
    });

    Schema::table('cities', function (Blueprint $table) {
      $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  public function down()
  {
    Schema::drop('cities');
  }
}
