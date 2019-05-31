<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\UserService;
use App\Services\AuthService;
use App\Http\Resources\AuthResource;
use Password;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected $userService;
    protected $authService;

    public function __construct(
        UserService $userService,
        AuthService $authService
    ) {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function sendResetTokenEmail(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        $updateToken = null;
        $user = $this->userService->getUserByEmail($email);

        if ($user) {
            $resetToken = $this->authService->generateResetToken($user);

            $this->authService->queueMailResetPassword($user, $resetToken);

            $updateToken = $this->authService->createResetPasswordToken($request->email, $resetToken);
        }

        return new AuthResource($updateToken, __FUNCTION__, __('passwords.sent'));
    }

    public function reset(ResetPasswordRequest $request)
    {
        $params = $request->only('password', 'token');
        $reset = $this->authService->resetPassword($params['password'], $params['token']);

        return new AuthResource($reset, 'resetPassword', __('passwords.reset'));
    }
}
