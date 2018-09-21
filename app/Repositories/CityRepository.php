<?php
namespace App\Repositories;

use App\Models\City;

class CityRepository extends BaseRepository
{
  function __construct(City $model)
  {
    $this->model = $model;
  }

  function getAllByStateId($id)
  {
    return $this->model->newQuery()->where('state_id', $id)
      ->orderBy('name', 'asc')
      ->get();
  }
}