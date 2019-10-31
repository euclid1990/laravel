<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckExtension;
use App\Rules\CheckImportFile;
use Illuminate\Support\Facades\Validator;

class ImportFileCsvRequest extends FormRequest
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
        $cfg = config('common.import.validation.file');

        return [
            'file' => [
                'bail',
                'required',
                'max:' . $cfg['max'],
                new CheckExtension($cfg['type']),
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $v = Validator::make(['file' => $this->file], [
                'file' => [new CheckImportFile($this)],
            ]);
            $v->validate();
        }
    }

    public function messages()
    {
        return [
            'file.required' => trans('import.message.file_required'),
            'file.max' => trans('import.message.file_max', ['max' => config('common.import.validation.file.max')]),
        ];
    }
}
