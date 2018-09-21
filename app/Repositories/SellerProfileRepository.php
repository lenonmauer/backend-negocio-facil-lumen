<?php
namespace App\Repositories;

use App\Models\SellerProfile;

class SellerProfileRepository extends BaseRepository
{
  function __construct(SellerProfile $model)
  {
    $this->model = $model;
  }

  function updateOrCreate($profile_id, $user_id, $data)
  {
    $condition = [
      'id' => $profile_id,
      'user_id' => $user_id,
    ];

    return $this->model->updateOrCreate($condition, $data);
  }
}