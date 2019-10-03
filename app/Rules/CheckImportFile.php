<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class CheckImportFile implements Rule
{
    /**
     * Initialized with each error received
     */
    protected $message;

    /**
     * Initialization to add elements to the request
     */
    protected $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->message = trans('import.message.message_default');
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $path = $value['file'];
        $file = fopen($path, 'r');
        $result = $this->check($file);
        fclose($file);
        return $result;
    }

    /**
     * Check the file title
     *
     * @param   array $firstLine
     * @return bool
     */
    protected function isValidHeader($firstLine)
    {
        $header = config('common.import.validation.file.header');
        $map = array_map(function ($transHeader) {
            return trans($transHeader);
        }, array_values($header));
        return $firstLine === $map;
    }

    /**
     * Check the file content on each line
     *
     * @param   array $nextLine
     * @param   array $totalColumn
     * @param   int $row
     * @return bool
     */
    protected function isVaildRow($nextLine, $totalColumn, $row)
    {
        if (count($totalColumn) !== count($nextLine)) {
            $this->message = trans('import.message.error_element', ['row' => $row]);
            return false;
        }

        foreach ($nextLine as $key => $value) {
            if (mb_strlen($value) > config('common.import.validation.name.max')) {
                $column = $key + 1;
                $this->message = trans('import.message.error_row_name', [
                    'row' => $row,
                    'column' => $column,
                ]);
                return false;
            }
        }

        return true;
    }

    /**
     * Check the file before importing
     *
     * @param   array $file
     * @return bool
     */
    protected function check($file)
    {
        $firstLine = fgetcsv($file);
        if (!$this->isValidHeader($firstLine)) {
            $this->message = trans('import.message.error_header');
            return false;
        }

        $row = 1;
        $insert = [];
        $nextLine = fgetcsv($file);
        $totalColumn = config('common.import.validation.file.header');
        while ($nextLine !== false) {
            $result = $this->isVaildRow($nextLine, $totalColumn, $row);
            if ($result !== true) {
                return false;
            }
            $row++;
            $insert[] = array_combine(array_keys($totalColumn), $nextLine);
            $nextLine = fgetcsv($file);
        }
        $this->request->merge(['data' => $insert]);
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
