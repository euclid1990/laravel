<?php

namespace Tests\Feature\Http\Controller\Web\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ResetPasswordControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $authService;

    public function setUp(): void
    {
        parent::setUp();
        $this->authService = $this->app->make('App\Services\AuthService');
    }

    public function testUserCanViewResetForm()
    {
        $response = $this->get(route('password.reset', ['token' => 'sometoken']));
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.reset');
    }

    public function testUserCanResetWithCorrectCredentials()
    {
        $user = factory(User::class)->create([
            'password' => $password = '12345678',
        ]);

        $resetToken = $this->authService->generateResetToken($user);

        $response = $this->from(route('password.request'))->post(route('password.update'), [
        		'token' => $resetToken,
                'password' => $password,
                'password_confirmation' => $password,
            ])
            ->assertStatus(302);
        $response->assertRedirect(route('password.request'));
    }

    public function testUserCannotResetWithInCorrectToken()
    {
        $user = factory(User::class)->create([
            'password' => $password = '12345678',
        ]);

        $resetToken = $this->authService->generateResetToken($user);

        $response = $this->from(route('password.reset', ['token' => 'someinvalidtoken']))->post(route('password.update'), [
        		'token' => 'someinvalidtoken',
                'password' => $password,
                'password_confirmation' => $password,
            ])
            ->assertStatus(302);
        $response->assertRedirect(route('password.reset', ['token' => 'someinvalidtoken']));
    }
}
