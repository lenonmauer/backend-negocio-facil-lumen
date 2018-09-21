<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoImoveisTable extends Migration
{
  public function up()
  {
    Schema::create('tipo_imoveis', function (Blueprint $table) {
      $table->increments('id');
      $table->string('nome');
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('tipo_imoveis');
  }
}
