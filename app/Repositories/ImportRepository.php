<?php

namespace App\Repositories;

use Illuminate\Database\Connection;
use Illuminate\Support\Collection;

class ImportRepository implements ImportRepositoryInterface
{
    /**
     * @var \Illuminate\Database\Connection
     */
    protected $db;

    /**
     *
     * @param \Illuminate\Database\Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query()
    {
        return $this->db->table('imports');
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function insert(array $data)
    {
        return $this->query()->insert($data);
    }
}
