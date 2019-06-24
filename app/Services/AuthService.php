<?php

namespace App\Services;

use Carbon\Carbon;
use App\Repositories\PasswordResetTokenRepository;
use App\Repositories\UserRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Mail\MailForgotPassword;
use Mail;
use Password;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Validation\ValidationException;

class AuthService extends AppService
{
    protected $resetPwdRepo;
    protected $userRepository;
    protected $loginRedirectTo = '/';

    public function __construct(
        PasswordResetTokenRepository $resetPwdRepo,
        UserRepositoryInterface $userRepository
    ) {
        parent::__construct($resetPwdRepo);

        $this->resetPwdRepo = $resetPwdRepo;
        $this->userRepository = $userRepository;
    }

    public function createResetPasswordToken($email, $token)
    {
        return $this->resetPwdRepo->store([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
    }

    public function queueMailResetPassword($user, $token, $url = null)
    {
        return Mail::queue(new MailForgotPassword($user, $token, $url));
    }

    public function resetPassword($password, $token)
    {
        $revokedToken = $this->resetPwdRepo->revokeTokenGetEmail($token);
        $user = $this->userRepository->getUserByEmail($revokedToken->email);
        $user->password = $password;
        return $user->save();
    }

    public function generateResetToken($user)
    {
        return Password::broker()->createToken($user);
    }

    public function createPassportRequest(array $data, $scope = '*', string $grantType = 'password')
    {
        $data['scope'] = $scope;
        $data['grant_type'] = $grantType;
        $data['client_id'] = env('API_CLIENT_ID');
        $data['client_secret'] = env('API_CLIENT_SECRET');

        return app(ServerRequestInterface::class)->withParsedBody($data);
    }

    public function attempt(Request $request)
    {
        return auth()->guard()->attempt($request->only($this->username(), 'password'), $request->filled('remember'));
    }

    public function username()
    {
        return 'email';
    }
}
