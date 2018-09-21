<?php
namespace App\Repositories;

use App\Models\Imovel;

class ImovelRepository extends BaseRepository
{
  function __construct(Imovel $model)
  {
    $this->model = $model;
  }
}
