<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $cfg = config('common.export');

        return [
            'file_type' => [
                'required',
                'in:' . implode(',', array_keys($cfg['types'])),
            ],
            'separation' => Rule::requiredIf(function () use ($cfg) {
                // phpcs:ignore Squiz.NamingConventions.ValidVariableName
                return $this->file_type === $cfg['types']['csv']['value'];
            }),
            'encoding' => [
                'required',
                'in:' . implode(',', array_keys($cfg['encoding'])),
            ],
            'export_column' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'separation.required' => trans('export.message.separation_required'),
            'encoding.required' => trans('export.message.encoding_required'),
            'encoding.in' => trans('export.message.encoding_in'),
            'file_type.required' => trans('export.message.file_type_required'),
            'file_type.in' => trans('export.message.file_type_in'),
            'export_column.required' => trans('export.message.export_column_required'),
        ];
    }
}
