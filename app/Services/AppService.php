<?php

namespace App\Services;

use App\Repositories\AppRepositoryInterface;

abstract class AppService
{
    protected $repository;

    public function __construct(AppRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getModel()
    {
        return $this->repository->getModel();
    }

    public function fetchAll(array $columns = ['*'])
    {
        return $this->repository->fetchAll($columns);
    }

    public function fetchList()
    {
        return $this->repository->fetchList();
    }

    public function paginateList($params)
    {
        $page = isset($params['page']) ? $params['page'] : config('common.page_default');

        return $this->repository->paginateList($page);
    }

    public function findById($id, array $columns = ['*'])
    {
        return $this->repository->findById($id, $columns);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(array $data)
    {
        return $this->repository->delete($data);
    }
}
