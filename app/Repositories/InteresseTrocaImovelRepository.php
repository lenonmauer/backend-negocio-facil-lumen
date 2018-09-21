<?php
namespace App\Repositories;

use App\Models\InteresseTrocaImovel;

class InteresseTrocaImovelRepository extends BaseRepository
{
  function __construct(InteresseTrocaImovel $model)
  {
    $this->model = $model;
  }

  function getByIdAndUser($id, $user_id)
  {
    return $this->model->newQuery()
      ->where('id', $id)
      ->where('user_id', $user_id)
      ->first();
  }
}
