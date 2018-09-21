<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatesTable extends Migration
{
  public function up()
  {
    Schema::create('states', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 64)->unique();
      $table->string('abbr', 2)->unique();
    });
  }

  public function down()
  {
    Schema::drop('states');
  }
}
