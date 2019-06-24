<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Web\WebController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;
use App\Services\UserService;
use App\Services\AuthService;

class ForgotPasswordController extends WebController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->middleware('guest');
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  App\Http\Requests\Auth\ForgotPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $email = $request->email;
        $updateToken = null;
        $user = $this->userService->getUserByEmail($email);

        if ($user) {
            $resetToken = $this->authService->generateResetToken($user);

            $this->authService->queueMailResetPassword($user, $resetToken, route('password.reset', ['token' => $resetToken]));

            $updateToken = $this->authService->createResetPasswordToken($request->email, $resetToken);
        }

        return back()->with('status', __('passwords.sent'));
    }
}
