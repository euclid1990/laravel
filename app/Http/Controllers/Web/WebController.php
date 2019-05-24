<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class WebController extends Controller
{
    protected $prefix;

    public function __construct()
    {
        $this->prefix  = config('web.view_prefix');
    }

    protected function render(array $data = [], string $view = null)
    {
        $view = $view ?: $this->view;
        $compacts = array_merge($this->compacts, $data);
        return view($this->prefix . $view, $compacts);
    }
}
