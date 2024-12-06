<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PushTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test case for index function.
     */
    public function testForIndexFunction(): void
    {
        $response = $this->get('/push');

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Channel List');
    }

    /**
     * Test case for create function.
     */
    public function testForCreateFunction(): void
    {
        $response = $this->get('/push/create');

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Create New Channel');
    }

    /**
     * Test case for store function.
     */
    public function testForStoreFunctionWithEmptyData(): void
    {
        $request = [];
        $response = $this->from('/push/create')
            ->post('/push', $request);

        $response->assertStatus(302);
        $response->assertRedirect('/push/create');
        $response->assertSessionHasErrors([
            'provider' => 'The provider field is required.',
            'name' => 'The name field is required.',
            'credentials' => 'The credentials field is required.',
        ]);
    }

    /**
     * Test case for store function.
     */
    public function testForStoreFunctionWithWrongData(): void
    {
        $request = [
            'provider' => 100,
            'name' => 't',
            'credentials' => 't',
        ];
        $response = $this->from('/push/create')
            ->post('/push', $request);

        $response->assertStatus(302);
        $response->assertRedirect('/push/create');

        $response->assertSessionHasErrors([
            'provider' => 'The selected provider is invalid.',
            'credentials' => 'The credentials field must be at least 10 characters.',
        ]);
    }

    /**
     * Test case for store function.
     */
    public function testForStoreFunctionWithRightData(): void
    {
        $request = [
            'provider' => 1,
            'name' => 'pusher testing',
            'credentials' => 'app_id = "1885"
            key = "26c0723"
            secret = "80e7f5"
            cluster = "ad1"',
        ];

        $response = $this->from('/push/create')
            ->post('/push', $request);

        $this->assertDatabaseHas('pushes', [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push');
    }

    /**
     * Test case for show function.
     */
    public function testForShowFunction(): void
    {
        $response = $this->get('/push/1');

        $response->assertStatus(200);
        $response->assertSee('show');
    }

    /**
     * Test case for edit function.
     */
    public function testForEditFunction(): void
    {
        $response = $this->get('/push/1/edit');

        $response->assertStatus(200);
        $response->assertSee('edit');
    }

    /**
     * Test case for update function.
     */
    public function testForUpdateFunction(): void
    {
        $request = [];

        $response = $this->patch('/push/1/update', $request);

        $response->assertStatus(200);
        $response->assertSee('update');
    }
}
