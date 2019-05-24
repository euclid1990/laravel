<?php

namespace App\Repo;

interface AppRepositoryInterface
{
    public function getModel();

    /**
     * @param array $columns
     * @param int $offset
     *
     * @return \Illuminate\Support\Collection
     */
    public function fetchList(array $columns = ['*'], int $offset = 0);

    /**
     * @param array $columns
     * @param int $id
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateList(int $page = null, array $columns = ['*'], string $orderBy = 'updated_at', string $orderDes = 'desc');

    /**
     * @param $id
     * @param array $columns
     *
     * @return object|null
     */
    public function findById(int $id, array $columns = ['*']);

    /**
     * @param array $data
     *
     * @return int
     */
    public function store(array $data);

    /**
     * @param $id
     * @param array $data
     *
     * @return int
     */
    public function update(int $id, array $data);

    /**
     * @param array $data
     *
     * @return boolean
     */
    public function deleteMany(array $data);

    /**
     * @param array $data
     *
     * @return boolean
     */
    public function deleteById(int $id);

    /**
     * @param array $columns
     *
     * @return boolean
     */
    public function fetchAll(array $columns = ['*']);

    public function insert(array $data);

    /**
     * @param array $ids
     * @param array $columns
     * @param string $orderBy
     * @param string $orderDes
     *
     * @return mixed
     */
    public function getListByIds(array $ids, array $columns = ['*'], string $orderBy = 'id', string $orderDes = 'ASC');
}
