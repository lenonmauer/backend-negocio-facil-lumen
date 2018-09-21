<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInteressesTrocaImoveisTable extends Migration
{
  public function up()
  {
    Schema::create('interesses_troca_imoveis', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->integer('imovel_meu_id')->unsigned();
      $table->integer('imovel_interesse_id')->unsigned();
      $table->string('resumo')->nullable();
      $table->timestamps();
    });

    Schema::table('interesses_troca_imoveis', function (Blueprint $table) {
      $table
        ->foreign('user_id')
        ->references('id')
        ->on('users')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      $table
        ->foreign('imovel_meu_id')
        ->references('id')
        ->on('imoveis')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      $table
        ->foreign('imovel_interesse_id')
        ->references('id')
        ->on('imoveis')
        ->onDelete('cascade')
        ->onUpdate('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('interesses_troca_imoveis');
  }
}
