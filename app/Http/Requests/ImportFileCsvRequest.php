<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'file' => 'required|mimes:csv|max:20480',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Can not blank file !',
            'file.mimes' => 'This is not a csv file',
            'file.max' => 'The file is smaller than 20480 kb',
        ];
    }
}
