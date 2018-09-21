<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImoveisTable extends Migration
{
  public function up()
  {
    Schema::create('imoveis', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->integer('tipo_imovel')->unsigned();
      $table->enum('categoria_imovel', ['NOVO', 'USADO']);
      $table->decimal('loc_lat', 10, 6);
      $table->decimal('loc_lng', 10, 6);
      $table->integer('quantidade_quartos')->unsigned();
      $table->integer('vagas_garagem')->unsigned();
      $table->integer('medida_m2')->unsigned();
      $table->timestamps();
    });

    Schema::table('imoveis', function (Blueprint $table) {
      $table
        ->foreign('user_id')
        ->references('id')
        ->on('users')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      $table
        ->foreign('tipo_imovel')
        ->references('id')
        ->on('tipo_imoveis')
        ->onDelete('cascade')
        ->onUpdate('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('imoveis');
  }
}
