<?php

namespace Tests\Feature\Web\App;

use App\Models\EmailChannel;
use App\Models\PushChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppCreateTest extends TestCase
{
    use RefreshDatabase;

    private $createPageURL = '/app/create';
    private $storePageURL = '/app';
    private $request = [
        'name' => 'Unit Testing',
        'description' => 'Unit Testing',
    ];

    /**
     * Test case for create page.
     */
    public function testCreatePage(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)
            ->get($this->createPageURL);

        $response->assertStatus(200);
        $response->assertSee('Create New App');
    }

    /**
     * Test case for store function with empty request.
     */
    public function testStoreFunctionWithEmptyRequest(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)
            ->from($this->createPageURL)
            ->post($this->storePageURL, []);

        $response->assertStatus(302);
        $response->assertRedirect($this->createPageURL);
    }

    /**
     * Test case for store function with invalid request.
     */
    public function testStoreFunctionWithInvalidRequest(): void
    {
        $user = User::first();
        $request = $this->request;
        $request['name'] = '';
        $request['services'] = ['test'];

        $response = $this->actingAs($user)
            ->from($this->createPageURL)
            ->post($this->storePageURL, $request);

        $response->assertStatus(302);
        $response->assertRedirect($this->createPageURL);
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'services.0' => 'The selected service is invalid.',
            'channels' => 'The channels field is required.',
        ]);
    }

    /**
     * Test case for store function with valid request (Only Push).
     */
    public function testStoreFunctionWithPushOnlyValidRequest(): void
    {
        $user = User::first();
        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->request;
        $request['services'] = ['push'];
        $request['channels'] = ['push' => $pushChannel->id];

        $response = $this->actingAs($user)
            ->from($this->createPageURL)
            ->post($this->storePageURL, $request);

        $this->assertDatabaseHas('apps', [
            'user_id' => 1,
            'name' => $request['name'],
            'description' => $request['description'],
            'scopes->notifications.push' => $pushChannel->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/app');
    }

    /**
     * Test case for store function with valid request (Only Email).
     */
    public function testStoreFunctionWithEmailOnlyValidRequest(): void
    {
        $user = User::first();
        $emailChannel = EmailChannel::factory(1)
            ->create()
            ->first();

        $request = $this->request;
        $request['services'] = ['email'];
        $request['channels'] = ['email' => $emailChannel->id];

        $response = $this->actingAs($user)
            ->from($this->createPageURL)
            ->post($this->storePageURL, $request);

        $this->assertDatabaseHas('apps', [
            'user_id' => 1,
            'name' => $request['name'],
            'description' => $request['description'],
            'scopes->notifications.email' => $emailChannel->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/app');
    }

    /**
     * Test case for store function with valid request.
     */
    public function testStoreFunctionWithValidRequest(): void
    {
        $user = User::first();

        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $emailChannel = EmailChannel::factory(1)
            ->create()
            ->first();

        $request = $this->request;
        $request['services'] = ['push', 'email'];
        $request['channels'] = [
            'push' => $pushChannel->id,
            'email' => $emailChannel->id
        ];

        $response = $this->actingAs($user)
            ->from($this->createPageURL)
            ->post($this->storePageURL, $request);

        $this->assertDatabaseHas('apps', [
            'user_id' => 1,
            'name' => $request['name'],
            'description' => $request['description'],
            'scopes->notifications.push' => $pushChannel->id,
            'scopes->notifications.email' => $emailChannel->id,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/app');
    }
}
