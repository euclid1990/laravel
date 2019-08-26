<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\ImportFileCsvRequest;
use App\Http\Controllers\Controller;
use App\Services\ImportService;

class ImportController extends Controller
{
    protected $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        return view('imports.index');
    }

    public function import(ImportFileCsvRequest $request)
    {
        $this->importService->importFile($request->get('data'));

        return redirect(route('import.create'))->with('message', trans('import.message.success'));
    }
}
