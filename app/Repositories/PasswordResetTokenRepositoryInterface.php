<?php

namespace App\Repositories;

interface PasswordResetTokenRepositoryInterface extends AppRepositoryInterface
{
    public function revokeTokenGetEmail($token);

    public function getObjectToken($token);
}
