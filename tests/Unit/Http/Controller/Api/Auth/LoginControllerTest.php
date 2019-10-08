<?php

namespace Tests\Unit\Http\Controller\Api\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\UserService;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use Mockery;
use App\Http\Controllers\Api\Auth\LoginController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Laravel\Passport\Token as PassportToken;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Auth\AuthenticationException;
use Auth;
use Illuminate\Http\Response;

class LoginControllerTest extends TestCase
{
    protected $userService;
    protected $authService;

    public function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->userService = Mockery::mock($this->app->make(UserService::class));
            $this->authService = Mockery::mock($this->app->make(AuthService::class));
        });

        parent::setUp();
    }

    protected function makeController($mockPartial = false): LoginController
    {
        if ($mockPartial) {
            return Mockery::mock(LoginController::class, [
                    app()->make(AuthorizationServer::class),
                    app()->make(TokenRepository::class),
                    app()->make(JwtParser::class),
                    $this->userService,
                    $this->authService,
                ])
                ->makePartial()
                ->shouldAllowMockingProtectedMethods();
        }

        return app()->make(LoginController::class, [
            'userService' => $this->userService,
            'authService' => $this->authService,
        ]);
    }

    public function testLogin()
    {
        $params = [
            'email' => 'admin@example.com',
            'password' => 'Aa@123456',
        ];
        $expectedResult = [
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'access_token' => 'access_token',
            'refresh_token' => 'refresh_token',
        ];

        $request = new LoginRequest();
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->setJson(new ParameterBag($params));

        $loginController = $this->makeController(true);

        $loginController->shouldReceive('passportIssueToken')
            ->once()
            ->andReturn($expectedResult);

        $response = $loginController->login($request, $this->authService);
        $data = $response->resource;
        $this->assertEquals($expectedResult, $data);
    }

    public function testLogout()
    {
        $loginController = $this->makeController();

        $expected = [
            'data' => null,
            'message' => __('auth.logout'),
            'code' => 200,
        ];

        $user = factory(User::class)->make();
        $token = Mockery::mock(PassportToken::class)->makePartial();
        $token->shouldReceive('revoke')
            ->once()
            ->andReturn(true);

        $user->withAccessToken($token);

        $guard = Auth::guard('api');
        $guard->setUser($user);

        Auth::shouldReceive('guard')
            ->with('api')
            ->once()
            ->andReturn($guard);

        $request = app()->make(Request::class);
        $response = $loginController->logout($request);
        $resData = json_decode($response->response()->content(), true);
        $this->assertArraySubSet($expected, $resData);
    }

    public function testRegister()
    {
        $params = [
            'name' => 'TienNH',
            'email' => 'admin@example.com',
            'password' => 'Aa@123456',
            'password' => 'Aa@123456',
        ];
        $expectedResult = [
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'access_token' => 'access_token',
            'refresh_token' => 'refresh_token',
        ];

        $request = new RegisterRequest();
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->setJson(new ParameterBag($params));

        $loginController = $this->makeController(true);

        $this->userService->shouldReceive('store')
            ->once()
            ->andReturn(true);

        $loginController->shouldReceive('passportIssueToken')
            ->once()
            ->andReturn($expectedResult);

        $response = $loginController->register($request, $this->authService);
        $data = $response->resource;
        $this->assertEquals($expectedResult, $data);
    }

    public function testRefresh()
    {
        $loginController = $this->makeController(true);

        $expected = [
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'access_token' => 'access_token',
            'refresh_token' => 'refresh_token',
        ];

        $request = app()->make(Request::class);
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->setJson(new ParameterBag([]));
        $loginController->shouldReceive('passportIssueToken')
            ->once()
            ->andReturn($expected);

        $response = $loginController->refreshToken($request);
        $resData = json_decode($response->response()->content(), true);
        $this->assertEquals($expected, $resData['data']);
    }

    public function testPassportIssueToken()
    {
        $loginController = $this->makeController(true);

        $passportRequest = app(ServerRequestInterface::class)->withParsedBody([
            'scope' => '*',
            'grant_type' => 'password',
            'client_id' => env('API_CLIENT_ID'),
            'client_secret' => env('API_CLIENT_SECRET'),
        ]);

        $expectedData = [
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'access_token' => 'access_token',
            'refresh_token' => 'refresh_token',
        ];

        $passportResponse = app(Response::class);
        $passportResponse->setContent($expectedData);

        $this->authService
            ->shouldReceive('createPassportRequest')
            ->once()
            ->andReturn($passportRequest);

        $loginController->shouldReceive('issueToken')
            ->once()
            ->andReturn($passportResponse);

        $returnData = $loginController->passportIssueToken([]);
        $this->assertEquals($expectedData, $returnData);
    }

    public function testPassportIssueTokenThrowException()
    {
        $loginController = $this->makeController(true);

        $passportRequest = app(ServerRequestInterface::class)->withParsedBody([
            'scope' => '*',
            'grant_type' => 'password',
            'client_id' => env('API_CLIENT_ID'),
            'client_secret' => env('API_CLIENT_SECRET'),
        ]);

        $expectedData = [
            'token_type' => 'Bearer',
            'expires_in' => 3600,
            'access_token' => 'access_token',
            'refresh_token' => 'refresh_token',
        ];

        $passportResponse = app(Response::class);
        $passportResponse->setStatusCode(Response::HTTP_UNAUTHORIZED);

        $this->authService
            ->shouldReceive('createPassportRequest')
            ->once()
            ->andReturn($passportRequest);

        $loginController->shouldReceive('issueToken')
            ->once()
            ->andReturn($passportResponse);

        try {
            $returnData = $loginController->passportIssueToken([]);
        } catch (\Exception $e) {
            $this->assertInstanceOf(AuthenticationException::class, $e);
        }
    }
}
