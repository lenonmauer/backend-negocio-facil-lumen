<?php
namespace App\Repositories;

use App\Models\State;

class StateRepository extends BaseRepository
{
  function __construct(State $model)
  {
    $this->model = $model;
  }
}