<?php
namespace App\Services;

use App\Repositories\ImportRepositoryInterface;

class ImportService
{
    /**
     * @var \App\Repositories\ImportRepositoryInterface
     */
    protected $repoImport;

    public function __construct(ImportRepositoryInterface $repoImport)
    {
        $this->repoImport = $repoImport;
    }

    public function importFile($importData)
    {
        return $this->repoImport->insert($importData);
    }
}
