<?php

namespace Tests\Unit\Http\Controller\Api\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Http\Resources\AuthResource;
use App\Services\UserService;
use App\Services\AuthService;
use App\Models\User;
use App\Models\PasswordResetToken;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Mockery;

class ForgotPasswordControllerTest extends TestCase
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

    public function testSendResetTokenEmail()
    {
        $user = factory(User::class)->make();
        $resetToken = 'reset_token';
        $email = 'admin@example.com';
        $pwdResetToken = app()->make(PasswordResetToken::class);
        $pwdResetToken->email = $email;
        $pwdResetToken->token = $resetToken;

        $params = [
            'email' => 'admin@example.com',
        ];

        $this->userService->shouldReceive('getUserByEmail')
            ->once()
            ->andReturn($user);

        $this->authService->shouldReceive('generateResetToken')
            ->once()
            ->andReturn($resetToken);

        $this->authService->shouldReceive('queueMailResetPassword')
            ->once()
            ->andReturn(null);

        $this->authService->shouldReceive('createResetPasswordToken')
            ->once()
            ->andReturn($pwdResetToken);

        $request = new ForgotPasswordRequest();
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->setJson(new ParameterBag($params));
        $controller = app()->make(ForgotPasswordController::class, [
            'userService' => $this->userService,
            'authService' => $this->authService,
        ]);

        $response = $controller->sendResetTokenEmail($request, $this->authService);
        $this->assertInstanceOf(AuthResource::class, $response);
        $data = $response->resource->getAttributes();
        $this->assertEquals($data['email'], $email);
        $this->assertEquals($data['token'], $resetToken);
    }
}
