<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
  public function up()
  {
  	Schema::create('users', function (Blueprint $table) {
  		$table->increments('id');
  		$table->string('email')->unique();
      $table->string('password');
      $table->timestamp('last_login')->nullable();
      $table->boolean('email_confirmed')->default(false);
  		$table->boolean('admin')->default(false);
      $table->timestamps();
    });
  }

  public function down()
  {
  	Schema::dropIfExists('users');
  }
}
