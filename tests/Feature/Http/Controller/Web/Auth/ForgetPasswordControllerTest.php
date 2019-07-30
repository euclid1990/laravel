<?php

namespace Tests\Feature\Http\Controller\Web\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class ForgetPasswordControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testUserViewLinkRequestForm()
    {
        $response = $this->get(route('password.request'));
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    public function testUserReceivesAnEmailWithAPasswordResetLink()
    {
        Mail::fake();
        $user = factory(User::class)->create([
            'email' => 'phamtritrung39@gmail.com',
            'password' => $password = '12345678',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $this->assertNotNull($token = DB::table('password_resets')->first());

        Mail::assertQueued(\App\Mail\MailForgotPassword::class, function ($mail) use ($user) {
            $fakeMail = new \ReflectionClass($mail);
            $mailUser = $fakeMail->getProperty('user');
            $mailUser->setAccessible(true);
            $mailClone = $mailUser->getValue($mail);

            return $mailClone->id === $user->id;
        });
    }
}
