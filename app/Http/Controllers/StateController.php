<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StateRepository;
use App\Repositories\CityRepository;

class StateController extends Controller
{
  public function getStates(StateRepository $stateRepo)
  {
    $states = $stateRepo->getAll('name');

    return response()->json([
      'states' => $states->toArray()
    ], 200);
  }

  public function getCities(CityRepository $cityRepo, $state_id)
  {
    $cities = $cityRepo->getAllByStateId($state_id);

    return response()->json([
      'cities' => $cities->toArray()
    ], 200);
  }
}
