<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run()
  {
    $this->call(StatesTableSeeder::class);
    $this->call(CitiesTableSeeder::class);
    $this->call(TipoImoveisTableSeeder::class);
  }
}
