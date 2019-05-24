<?php

namespace App\Repo;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class AppRepository implements AppRepositoryInterface
{
    protected $model;

    /**
     * PostRepository constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $columns
     * @param int $offset
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchList(array $columns = ['*'], int $offset = 0)
    {
        $perPage = config('common.item_per_page');

        return $this->model
            ->skip($offset)
            ->limit($perPage)
            ->get($columns);
    }

    /**
     * @codeCoverageIgnore
     * @param null $page
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateList(int $page = null, array $columns = ['*'], string $orderBy = 'updated_at', string $orderDes = 'desc')
    {
        $perPage = config('common.item_per_page');

        return $this->model
            ->orderBy('updated_at', $orderDes)
            ->paginate($perPage, $columns, 'page', $page);
    }

    /**
     * @param $id
     * @param array $columns
     *
     * @return object|null
     */
    public function findById(int $id, array $columns = ['*'])
    {
        return $this->model
            ->find($id, $columns);
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $id
     * @param array $data
     *
     * @return int
     */
    public function update(int $id, array $data)
    {
        return $this->model
            ->where('id', $id)
            ->update($data);
    }

    /**
     * @param array $data
     *
     * @return boolean
     */
    public function deleteMany(array $data)
    {
        return $this->model
            ->whereIn('id', $data)
            ->delete();
    }

    /**
     * @param array $data
     *
     * @return boolean
     */
    public function deleteById(int $id)
    {
        return $this->model
            ->where('id', $id)
            ->delete();
    }

    /**
     * @param array $columns
     *
     * @return boolean
     */
    public function fetchAll(array $columns = ['*'])
    {
        return $this->model->get($columns);
    }

    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    public function getListByIds(array $ids, array $columns = ['*'], string $orderBy = 'id', string $orderDes = 'ASC')
    {
        return $this->model->whereIn('id', $ids)
            ->orderBy($orderBy, $orderDes)
            ->get($columns);
    }
}
