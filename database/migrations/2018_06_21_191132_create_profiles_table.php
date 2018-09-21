<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
  public function up()
  {
    Schema::create('profiles', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->integer('city_id')->unsigned()->nullable();
      // $table->integer('foto_file_id')->unsigned()->nullable();
      $table->string('foto_facebook_url')->nullable();
      $table->string('nome');
      $table->string('cpf')->nullable();
      $table->date('data_nascimento')->nullable();
      $table->string('profissao')->nullable();
      $table->string('phone_1')->nullable();
      $table->string('phone_2')->nullable();
      $table->string('logradouro')->nullable();
      $table->string('numero')->nullable();
      $table->string('bairro')->nullable();
      $table->string('cep')->nullable();
      $table->enum('estado_civil', ['SOLTEIRO', 'CASADO'])->nullable();
      $table->timestamps();
    });

    Schema::table('profiles', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
      $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
      // $table->foreign('foto_file_id')->references('id')->on('files')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('profiles');
  }
}
