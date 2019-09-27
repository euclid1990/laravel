<?php

namespace App\Libraries\FileManager\Facade;

use Illuminate\Support\Facades\Facade;

class FileManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filemanager';
    }
}
