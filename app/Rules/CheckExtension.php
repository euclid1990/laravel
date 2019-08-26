<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckExtension implements Rule
{
    /**
     * File type configuration is accepted
     */
    protected $parameters;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($parameters)
    {
        $this->parameters = $parameters;
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
        $extension = $value->getClientOriginalExtension();
        return in_array($extension, $this->parameters);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('import.message.file_mimes', ['format' => implode(', ', $this->parameters)]);
    }
}
