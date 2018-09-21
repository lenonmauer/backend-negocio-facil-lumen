<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
  public function up()
  {
    Schema::create('files', function (Blueprint $table) {
      $table->increments('id');
      $table->uuid('uuid');
      $table->integer('upload_user_id')->unsigned();
      $table->string('name');
      $table->string('path');
      $table->string('mime_type');
      $table->integer('size');
      $table->timestamps();
    });

    Schema::table('files', function (Blueprint $table) {
      $table->foreign('upload_user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('files');
  }
}
