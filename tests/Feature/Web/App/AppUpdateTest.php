<?php

namespace Tests\Feature\Web\App;

use App\Models\App;
use App\Models\PushChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppUpdateTest extends TestCase
{
    use RefreshDatabase;

    private $storePageURL = '/app';
    private $editPageURL = '/app/%s/edit';
    private $updatePageURL = '/app/%s/update';
    private $request = [
        'name' => 'Unit Testing',
        'description' => 'Unit Testing',
        'services' => ['push'],
    ];

    /**
     * Test case for update function with invalid id.
     */
    public function testUpdateFunctionWithInvalidId(): void
    {
        $user = User::first();

        $id = 1;
        $url = sprintf($this->updatePageURL, $id);
        $request = $this->request;

        $response = $this->actingAs($user)
            ->put($url, $request);

        // need to recheck why not 404.
        $response->assertStatus(302);
    }

    /**
     * Test case for update function with empty request.
     */
    public function testUpdateFunctionWithEmptyRequest(): void
    {
        $user = User::first();

        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->request;
        $request['channels'] = ['push' => $pushChannel->id];

        $this->actingAs($user)
            ->post($this->storePageURL, $request);

        $app = App::first();

        $editURL = sprintf($this->editPageURL, $app->id);
        $updateURL = sprintf($this->updatePageURL, $app->id);

        $response = $this->actingAs($user)
            ->from($editURL)
            ->put($updateURL, []);

        $response->assertStatus(302);
        $response->assertRedirect($editURL);
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'services' => 'The services field is required.',
            'channels' => 'The channels field is required.',
        ]);
    }

    /**
     * Test case for update function with invalid request.
     */
    public function testUpdateFunctionWithInvalidRequest(): void
    {
        $user = User::first();

        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->request;
        $request['channels'] = ['push' => $pushChannel->id];

        $this->actingAs($user)
            ->post($this->storePageURL, $request);

        $app = App::first();

        $editURL = sprintf($this->editPageURL, $app->id);
        $updateURL = sprintf($this->updatePageURL, $app->id);

        $request['name'] = '';
        $request['services'] = ['test'];
        unset($request['channels']);

        $response = $this->actingAs($user)
            ->from($editURL)
            ->put($updateURL, $request);

        $response->assertStatus(302);
        $response->assertRedirect($editURL);
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'services.0' => 'The selected service is invalid.',
            'channels' => 'The channels field is required.',
        ]);
    }

    /**
     * Test case for update function with valid request.
     */
    public function testUpdateFunctionWithValidRequest(): void
    {
        $user = User::first();

        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->request;
        $request['channels'] = ['push' => $pushChannel->id];

        $this->actingAs($user)
            ->post($this->storePageURL, $request);

        $app = App::first();

        $editURL = sprintf($this->editPageURL, $app->id);
        $updateURL = sprintf($this->updatePageURL, $app->id);

        $request['name'] .= ' Update';
        $request['description'] .= ' Update';

        $response = $this->actingAs($user)
            ->from($editURL)
            ->put($updateURL, $request);

        $this->assertDatabaseHas('apps', [
            'id' => $app->id,
            'user_id' => 1,
            'name' => $request['name'],
            'description' => $request['description'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/app');
    }
}
