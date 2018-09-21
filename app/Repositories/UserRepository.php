<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
  function __construct(User $model)
  {
    $this->model = $model;
  }

  public function getByEmail($email)
  {
    $query = $this->model->newQuery();

    $query->where('email', $email);

    return $query->first();
  }

  public function updateLastLoginToNow($id)
  {
    $data = [
      'last_login' => date('Y-m-d H:i:s'),
    ];

    return $this->updateById($id, $data);
  }
}