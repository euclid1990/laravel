<?php

namespace App\Libraries\FileManager\Handlers;

use Illuminate\Support\Facades\Storage;
use App\Libraries\FileManager\FileHandlerContract;
use App\Models\File as FileUpload;
use DB;
use Illuminate\Http\File as LaravelFile;

class DocFile extends FileHandlerContract
{
    protected $rules = 'required|mimes:xls,xlsx,csv';
    protected $type = 'doc';

    public function __construct(Storage $storage)
    {
        parent::__construct($storage);
        $this->data['type'] = $this->type;
    }

    protected function put($uploadFile, $path, $options)
    {
        DB::beginTransaction();
        $this->setUploadData($uploadFile, $path);

        if ($this->repository->store($this->data)) {
            if ($this->driver()->putFileAs($path, $uploadFile, $this->data['id'], $options)) {
                DB::commit();

                return $this->data['id'];
            } else {
                DB::rollback();

                return false;
            }
        }

        return false;
    }
}
