<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\ImportFileCsvRequest;
use App\Http\Controllers\Controller;
use App\Services\ImportService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    protected $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        $user = Auth::user();
        return view('imports.index', compact('user'));
    }

    public function import(ImportFileCsvRequest $request)
    {
        try {
            $this->importService->importFile($request->get('data'));

            return redirect(route('import.create'))->with('message', trans('import.message.success'));
        } catch (QueryException $e) {
            return redirect()->back()->withErrors([
                'errors' => trans('import.message.import_failed'),
            ]);
        }
    }
}
