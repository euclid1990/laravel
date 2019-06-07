<?php

namespace Tests\Feature\Http\Controller\Web\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserCanViewALoginForm()
    {
        $response = $this->get(route('login'));
        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    public function testUserCannotViewALoginFormWhenAuthenticated()
    {
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get(route('login'));
        $response->assertRedirect(route('home'));
    }

    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = factory(User::class)->create([
            'password' => $password = '12345678',
        ]);
        $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => $password,
            ])
            ->assertStatus(302);
        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function testUserCannotLoginWithInCorrectPassword()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('12345678'),
        ]);
        
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);
        
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    public function testRememberMeFunctionality()
    {
        $user = factory(User::class)->create([
            'password' => $password = '12345678',
        ]);
        
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
            'remember' => 'on',
        ]);
        
        $response->assertRedirect(route('home'));
        $response->assertCookie(auth()->guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));
        $this->assertAuthenticatedAs($user);
    }
}
