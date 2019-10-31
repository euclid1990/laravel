<?php
namespace App\Repositories;

use App\Models\Import;

class ExportRepository implements ExportRepositoryInterface
{
    /**
     * @var \App\Models\Import
     */
    protected $import;
    /**
     *
     * @param \App\Models\Import $import
     */
    public function __construct(Import $import)
    {
        $this->model = $import;
    }
    /**
     * @param array $data
     *
     * @return bool
     */
    public function select(array $data)
    {
        return $this->model->get($data)->toArray();
    }
}
