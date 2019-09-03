<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests\ExportFormRequest;
use App\Http\Controllers\Controller;
use App\Services\ExportService;
use App\Http\Resources\ExportResource;

class ExportController extends Controller
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function export(ExportFormRequest $request)
    {
        // phpcs:ignore Squiz.NamingConventions.ValidVariableName
        $fileType = $request->file_type;
        $fileName = $this->exportService->makeFilename($fileType);

        $data = $this->exportService->getData($fileType, $request);

        $types = config('common.export.types.' . $fileType . '.mime');

        if ($request->expectsJson()) {
            return new ExportResource([
                'fileName' => $fileName,
                'fileMime' => $types,
                'fileDataBase64' => base64_encode($data['encoded']),
                'fileData' => $data['raw'],
            ]);
        }

        return response()->streamDownload(function () use ($data) {
            echo $data;
        }, $fileName);
    }
}
