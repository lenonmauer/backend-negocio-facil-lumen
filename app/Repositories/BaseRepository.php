<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\AbstractPaginator as Paginator;

abstract class BaseRepository
{
  public function create(array $data)
  {
    return $this->model->create($data);
  }

  public function getAll($order = false, $take = false, $paginate = false)
  {
    $query = $this->model->newQuery();

    if ($paginate === true) {
      $query->paginate($take);
    }

    if ($order !== false) {
      $query->orderBy($order, 'asc');
    }

    if ($take !== false) {
      $query->take($take);
    }

    return $query->get();
  }

  public function getByID($id)
  {
    return $this->model->newQuery()->find($id);
  }

  public function updateById($id, $data)
  {
    return $this->model->newQuery()->where('id', $id)->update($data);
  }

  public function deleteById($id)
  {
    return $this->model->newQuery()->where('id', $id)->delete();
  }
}
