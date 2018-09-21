<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TipoImovelRepository;

class TipoImoveisController extends Controller
{
  public function getTipoImoveis(TipoImovelRepository $repo)
  {
    $records = $repo->getAll('nome');

    return response()->json(
      [
        'tipo_imoveis' => $records->toArray()
      ],
      200
    );
  }
}
