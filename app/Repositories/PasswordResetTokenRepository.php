<?php

namespace App\Repositories;

use App\Repositories\PasswordResetTokenRepositoryInterface;
use App\Models\PasswordResetToken;

class PasswordResetTokenRepository extends AppRepository implements PasswordResetTokenRepositoryInterface
{
    public function __construct(PasswordResetToken $model)
    {
        parent::__construct($model);
    }

    public function revokeTokenGetEmail($token)
    {
        $tokenObj = $this->getObjectToken($token);
        $tokenObj->revoked = 1;
        $tokenObj->save();

        return $tokenObj;
    }

    public function getObjectToken($token)
    {
        return $this->model
            ->where('token', $token)
            ->first();
    }
}
