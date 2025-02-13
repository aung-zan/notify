<?php

namespace Tests\Feature\Web\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private $loginPageURL = '/login';
    private $authenticateURL = '/login';
    private $logoutURL = '/logout';
    private $appIndexPageURL = '/app';
    private $credentials = [
        'email' => 'test@example.com',
        'password' => 'password'
    ];

    /**
     * Test case for login page.
     */
    public function testLoginPage(): void
    {
        $response = $this->get($this->loginPageURL);

        $response->assertStatus(200);
    }

    /**
     * Test case for authenticate function with empty request.
     */
    public function testAuthenticateWithEmptyRequest(): void
    {
        $response = $this->from($this->loginPageURL)
            ->post($this->authenticateURL, []);

        $response->assertStatus(302);
        $response->assertRedirect($this->loginPageURL);
        $response->assertSessionHasErrors([
            'email' => 'The email field is required.',
            'password' => 'The password field is required.',
        ]);
    }

    /**
     * Test case for authenticate function with invalid request.
     */
    public function testAuthenticateWithInvalidRequest(): void
    {
        $request = $this->credentials;
        $request['email'] = 'aa@test.com';

        $response = $this->from($this->loginPageURL)
            ->post($this->authenticateURL, $request);

        $response->assertStatus(302);
        $response->assertRedirect($this->loginPageURL);
        $response->assertSessionHas('flashMessage', 'The email and password are incorrect.');
    }

    /**
     * Test case for authenticate function with valid request.
     */
    public function testAuthenticateWithValidRequest(): void
    {
        $request = $this->credentials;

        $response = $this->from($this->loginPageURL)
            ->post($this->authenticateURL, $request);

        $response->assertStatus(302);
        $response->assertRedirect($this->appIndexPageURL);
    }

    /**
     * Test case for logout without authenticate.
     */
    public function testLogoutWithoutAuth(): void
    {
        $response = $this->post($this->logoutURL, []);

        $response->assertStatus(302);
        $response->assertRedirect($this->loginPageURL);
    }

    /**
     * Test case for logout with authenticate.
     * This function need to session checking codes.
     */
    public function testLogoutWithAuth(): void
    {
        $request = [];
        $response = $this->post($this->logoutURL, $request);

        $response->assertStatus(302);
        $response->assertRedirect($this->loginPageURL);
    }

    /**
     * Test case for app index page without authenticate.
     */
    public function testVisitOtherPageWithoutAuth(): void
    {
        $response = $this->get($this->appIndexPageURL);

        $response->assertStatus(302);
        $response->assertRedirect($this->loginPageURL);
    }

    /**
     * Test case for login with authenticate.
     */
    public function testVisitLoginPageWithAuth(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)
            ->get('/login');

        $response->assertStatus(302);
        $response->assertRedirect($this->appIndexPageURL);
    }
}
