<?php
namespace App\Services;

use App\Repositories\ExportRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class ExportService
{
    public function __construct(ExportRepositoryInterface $repoExport)
    {
        $this->repoExport = $repoExport;
    }

    /**
     * Create a file name data.
     *
     * @param string $fileType
     * @return string
     */
    public function makeFilename(string $fileType)
    {
        $current = Carbon::now()->toDateTimeString();
        $cfg = config('common.export');

        $fileName = '';

        if (in_array($fileType, array_keys($cfg['types']))) {
            $fileName = sprintf($cfg['file_name'], $current) . '.' . $fileType;
        }

        return $fileName;
    }

    /**
     * Retrieve data according to each file type
     *
     * @param string $fileType
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function getData(string $fileType, \Illuminate\Http\Request $request)
    {
        $cfg = config('common.export');
        switch ($fileType) {
            case $cfg['types']['csv']['value']:
                // phpcs:ignore Squiz.NamingConventions.ValidVariableName
                return $this->csv($cfg, $request->export_column, $request->separation, $request->encoding);
            case $cfg['types']['xlsx']['value']:
                return $this->excel();
        }
    }

    /**
     * Convert data to csv file
     *
     * @param array $cfg
     * @param array $exportColumn
     * @param string $separation
     * @param string $encoding
     * @return array
     */
    public function csv(array $cfg, array $exportColumn, string $separation, string $encoding)
    {
        // Compare column request and config
        $exportColumns = array_intersect($exportColumn, array_keys($cfg['export_column']));

        $data = $this->repoExport->select($exportColumns);

        // Create a title for each column
        $headerExport = [];
        foreach ($exportColumns as $key => $value) {
            $headerExport[] = trans('export.header_export.' . $value);
        }

        array_unshift($data, $headerExport);

        // Compare delimiter request  and config
        $delimiter = $separation;

        // Converts characters so that the function reads the required characters correctly
        if ($delimiter === "\\t") {
            $delimiter = "\t";
        }

        $result = '';

        foreach ($data as $row) {
            $result .= implode($delimiter, $row) . "\r\n";
        }
        if (in_array($encoding, array_keys(Arr::except(config('common.export.encoding'), ['utf8'])))) {
            $dataConvert = mb_convert_encoding($result, config('common.export.encoding.' . $encoding), $cfg['encoding']['utf8']);
        } else {
            $dataConvert = $result;
        }

        return $data = [
            'raw' => $result,
            'encoded' => $dataConvert,
        ];
    }

    /**
     * Convert data to excel file
     *
     * @return array
     */
    public function excel()
    {
        $result = '';
        return $data = [
            'raw' => $result,
            'encoded' => $result,
        ];
    }
}
