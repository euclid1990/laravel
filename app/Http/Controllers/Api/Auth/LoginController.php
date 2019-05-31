<?php

namespace App\Http\Controllers\Api\Auth;

use Laravel\Passport\Http\Controllers\AccessTokenController as PassportController;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\UserService;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Auth;

class LoginController extends PassportController
{
    protected $userService;
    protected $authService;

    public function __construct(
        AuthorizationServer $server,
        TokenRepository $tokens,
        JwtParser $jwt,
        UserService $userService,
        AuthService $authService
    ) {
        parent::__construct($server, $tokens, $jwt);
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $params = $request->all();
        $loginData = [
            'username' => $params['email'],
            'password' => $params['password'],
        ];

        $token = $this->passportIssueToken($loginData);

        return new AuthResource($token, __FUNCTION__);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $user->token()->revoke();
        }

        return new AuthResource([], 'logout', __('auth.logout'));
    }

    public function register(RegisterRequest $request)
    {
        $userInfo = $request->all();
        $this->userService->store($userInfo);

        $loginData = [
            'username' => $userInfo['email'],
            'password' => $userInfo['password'],
        ];

        $token = $this->passportIssueToken($loginData);

        return new AuthResource($token, __FUNCTION__);
    }

    public function refreshToken(Request $request)
    {
        $data = $request->all();
        $token = $this->passportIssueToken($data, '*', 'refresh_token');

        return new AuthResource($token, __FUNCTION__);
    }

    protected function passportIssueToken($data, $scope = '*', string $grantType = 'password')
    {
        $passportRequest = $this->authService->createPassportRequest($data, $scope, $grantType);
        $passportResponse = $this->issueToken($passportRequest);
        $responseContent = json_decode($passportResponse->getContent(), true);

        if ($passportResponse->status() === Response::HTTP_UNAUTHORIZED) {
            throw new AuthenticationException($responseContent['message']);
        }

        return $responseContent;
    }
}
