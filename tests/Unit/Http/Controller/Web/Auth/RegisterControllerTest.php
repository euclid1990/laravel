<?php

namespace Tests\Unit\Http\Controller\Web\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Session;
use App\Http\Requests\Auth\RegisterRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Http\RedirectResponse;

class RegisterControllerTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $controller;
    protected $manager;

    public function setUp(): void
    {
        parent::setUp();
        Session::setDefaultDriver('array');
        $this->manager = app('session');
        $this->controller = $this->app->make('App\Http\Controllers\Web\Auth\RegisterController');
    }

    public function testUserCanRegister()
    {
        $this->withoutMiddleware();

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        $request = new RegisterRequest();

        $request->headers->set('content-type', 'application/json');
        $request->setJson(new ParameterBag($data));
        $request->setMethod('POST');
        $request->setLaravelSession($this->manager->driver());

        $response = $this->controller->register($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('home'), $response->headers->get('Location'));
    }
}
