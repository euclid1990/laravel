<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository extends AppRepository implements FileRepositoryInterface
{
    public function __construct(File $model)
    {
        parent::__construct($model);
    }
}
