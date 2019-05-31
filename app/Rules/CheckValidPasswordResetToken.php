<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Repositories\PasswordResetTokenRepository;

class CheckValidPasswordResetToken implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $token = app(PasswordResetTokenRepository::class)->getObjectToken($value);
        return !$token || !$token->revoked;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('passwords.token');
    }
}
