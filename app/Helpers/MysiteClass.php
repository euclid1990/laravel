<?php

namespace App\Helpers;

use File;

class MySiteClass {

    public function getUserImage($path)
    {
        if(!File::exists($path)){
            $path = '/image/default.png';
        }
        return public_path($path);

    }

}