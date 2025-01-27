<?php

namespace Tests\Feature\Web\Email;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailCreateTest extends TestCase
{
    use RefreshDatabase;

    private $createPageURL = '/email/create';
    private $storePageURL = '/email';
    private $request = [
        'user_id' => 1,
        'provider' => 1,
        'name' => 'mail testing',
        'credentials' => 'MAIL_MAILER=smtp
        MAIL_HOST=sandbox.smtp.mailtrap.io
        MAIL_PORT=2525
        MAIL_USERNAME=1a601ae54273fa
        MAIL_PASSWORD=ec32bbd0f06979',
    ];

    /**
     * Test case for create page.
     */
    public function testCreatePage(): void
    {
        $response = $this->get($this->createPageURL);

        $response->assertStatus(200);
        $response->assertSee('Create New Channel');
    }

    /**
     * Test case for store function with empty request.
     */
    public function testStoreFunctionWithEmptyRequest(): void
    {
        $response = $this->from($this->createPageURL)
            ->post($this->storePageURL, []);

        $response->assertStatus(302);
        $response->assertRedirect($this->createPageURL);
        $response->assertSessionHasErrors([
            'provider' => 'The provider field is required.',
            'name' => 'The name field is required.',
            'credentials' => 'The credentials field is required.',
        ]);
    }

    /**
     * Test case for store function with invalid request.
     */
    public function testStoreFunctionWithInvalidRequest(): void
    {
        $request = $this->request;
        $request['name'] = '';
        $request['provider'] = 5;

        $response = $this->from($this->createPageURL)
            ->post($this->storePageURL, $request);

        $response->assertStatus(302);
        $response->assertRedirect($this->createPageURL);
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'provider' => 'The selected provider is invalid.',
        ]);
    }

    /**
     * Test case for store function with valid request.
     */
    public function testStoreFunctionWithValidRequest(): void
    {
        $request = $this->request;

        $response = $this->from($this->createPageURL)
            ->post($this->storePageURL, $request);

        $this->assertDatabaseHas('email_channels', [
            'user_id' => $request['user_id'],
            'name' => $request['name'],
            'provider' => $request['provider'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/email');
        $response->assertSessionHas('flashMessage.message', 'Successfully created.');
    }
}
