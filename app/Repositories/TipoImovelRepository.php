<?php
namespace App\Repositories;

use App\Models\TipoImovel;

class TipoImovelRepository extends BaseRepository
{
  function __construct(TipoImovel $model)
  {
    $this->model = $model;
  }
}
