<?php

namespace Tests\Unit\Http\Controller\Web\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Requests\Auth\LoginRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use Mockery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Session;
use Illuminate\Http\RedirectResponse;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $controller;
    protected $manager;

    public function setUp(): void
    {
        parent::setUp();
        Session::setDefaultDriver('array');
        $this->manager = app('session');
        $this->controller = $this->app->make('App\Http\Controllers\Web\Auth\LoginController');
    }

    public function testUserCanLoginWithCorrectCredentials()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create([
            'password' => $password = '123456',
        ]);

        $request = new LoginRequest();

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag([
            'email' => $user->email,
            'password' => $password,
        ]));
        $request->setMethod('POST');
        $request->setLaravelSession($this->manager->driver());

        $response = $this->controller->login($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('home'), $response->headers->get('Location'));
        $this->assertAuthenticatedAs($user);
    }
}
