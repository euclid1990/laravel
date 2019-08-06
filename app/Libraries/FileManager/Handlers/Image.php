<?php

namespace App\Libraries\FileManager\Handlers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File as LaravelFile;
use App\Libraries\FileManager\FileHandlerContract;
use App\Models\File as FileUpload;
use App\Repositories\FileRepositoryInterface;
use Intervention\Image\Facades\Image as ImageEditor;
use DB;

class Image extends FileHandlerContract
{
    protected $rules = 'mimes:jpeg,jpg,png,gif|required';
    protected $type = 'image';

    /**
     * resize data.
     *
     * @var string
     */
    protected $resize = [];

    public function __construct(FileRepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->data['type'] = $this->type;
    }

    protected function put($uploadImage, $path, $options)
    {
        DB::beginTransaction();
        $this->setUploadData($uploadImage, $path);

        if ($this->repository->store($this->data)) {
            $storeImage = $uploadImage;

            if ($this->resize != [] && count($this->resize) === 2) {
                $image = ImageEditor::make($uploadImage->getRealPath());

                $image->resize($this->resize[0], $this->resize[1], function ($constraint) {
                    $constraint->aspectRatio();
                })->save();

                $storeImage = new LaravelFile($image->dirname . '/' . $image->basename);
            }

            if ($this->driver()->putFileAs($path, $storeImage, $this->data['id'], $options)) {
                DB::commit();

                return $this->data['id'];
            } else {
                DB::rollback();

                return false;
            }
        }

        return false;
    }



    public function resize($width, $height)
    {
        $this->resize = [$width, $height];

        return $this;
    }
}
