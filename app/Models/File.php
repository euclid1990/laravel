<?php

namespace App\Models;

use Illuminate\Support\Collection;

class File extends BaseModel
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'id',
        'name',
        'type',
        'path',
        'bucket',
        'storage',
    ];

    public $timestamps = false;
}
