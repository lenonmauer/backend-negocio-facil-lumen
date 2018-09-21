<?php

use Illuminate\Database\Seeder;

class TipoImoveisTableSeeder extends Seeder
{
  public function run()
  {
    DB::table('tipo_imoveis')->insert([
      [
        'id' => 1,
        'nome' => 'Casas'
      ],
      [
        'id' => 2,
        'nome' => 'Apartamentos'
      ],
      [
        'id' => 3,
        'nome' => 'Geminados'
      ],
      [
        'id' => 4,
        'nome' => 'Terrenos e lotes'
      ],
      [
        'id' => 5,
        'nome' => 'Propriedade Rural'
      ],
      [
        'id' => 6,
        'nome' => 'Comercial'
      ]
    ]);
  }
}
