<?php
namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository extends BaseRepository
{
  function __construct(Profile $model) {
    $this->model = $model;
  }

  function updateProfileFoto($id, $foto_url)
  {
    $data = [
      'foto_facebook_url' => $foto_url,
    ];

    return $this->updateById($id, $data);
  }
}