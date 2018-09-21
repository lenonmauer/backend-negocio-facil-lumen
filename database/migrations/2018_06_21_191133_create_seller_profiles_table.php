<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSellerProfilesTable extends Migration
{
  public function up()
  {
    Schema::create('seller_profiles', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->integer('city_id')->unsigned()->nullable();
      $table->integer('logo_file_id')->unsigned()->nullable();
      $table->string('nome');
      $table->enum('tipo_pessoa', ['FISICA', 'JURIDICA'])->nullable();
      $table->string('cpf')->nullable();
      $table->string('cnpj')->nullable();
      $table->string('phone_1')->nullable();
      $table->string('phone_2')->nullable();
      $table->string('logradouro')->nullable();
      $table->string('numero')->nullable();
      $table->string('bairro')->nullable();
      $table->string('cep')->nullable();
      $table->string('resumo')->nullable();
      $table->string('site')->nullable();
      $table->timestamps();
    });

    Schema::table('seller_profiles', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
      $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
      $table->foreign('logo_file_id')->references('id')->on('files')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('seller_profiles');
  }
}
