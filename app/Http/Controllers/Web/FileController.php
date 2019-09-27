<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Libraries\FileManager\Facade\FileManager;

class FileController extends WebController
{
    public function index()
    {
        return view('file_upload');
    }

    public function upload(Request $request)
    {
        $file = $request->file('files');
        try {
            FileManager::handle('image')->storage('s3')->upload($file);

            return redirect()->back()->with('success', 'upload file success');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function localFileServe($filePath)
    {
        if (!Storage::disk('local')->exists($filePath)) {
            abort('404');
        }

        return response()->file(storage_path('app' . DIRECTORY_SEPARATOR . $filePath));
    }
}
