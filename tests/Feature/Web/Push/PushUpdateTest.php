<?php

namespace Tests\Feature\Web\Push;

use App\Models\PushChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PushUpdateTest extends TestCase
{
    use RefreshDatabase;

    private $editPageURL = '/push/%s/edit';
    private $updatePageURL = '/push/%s/update';
    private $request = [
        'provider' => 1,
        'name' => 'pusher testing',
    ];

    /**
     * Test case for update function with invalid id.
     */
    public function testUpdateFunctionWithInvalidId(): void
    {
        $id = 1;
        $url = sprintf($this->updatePageURL, $id);
        $request = $this->request;

        $response = $this->put($url, $request);

        $response->assertStatus(302);
        $response->assertSessionHas('flashMessage.message', 'The requested resource does not exit.');
    }

    /**
     * Test case for update function with empty request.
     */
    public function testUpdateFunctionWithEmptyRequest(): void
    {
        $request = $this->request;
        $pushChannel = PushChannel::factory(1)
            ->create($request)
            ->first();

        $editURL = sprintf($this->editPageURL, $pushChannel->id);
        $updateURL = sprintf($this->updatePageURL, $pushChannel->id);

        $response = $this->from($editURL)
            ->put($updateURL, []);

        $response->assertStatus(302);
        $response->assertRedirect($editURL);
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
        ]);
    }

    /**
     * Test case for update function with invalid request.
     */
    public function testUpdateFunctionWithInvalidRequest(): void
    {
        $request = $this->request;
        $pushChannel = PushChannel::factory(1)
            ->create($request)
            ->first();

        $editURL = sprintf($this->editPageURL, $pushChannel->id);
        $updateURL = sprintf($this->updatePageURL, $pushChannel->id);

        $request['name'] = '';
        $request['credentials'] = '';

        $response = $this->from($editURL)
            ->put($updateURL, $request);

        $response->assertStatus(302);
        $response->assertRedirect($editURL);
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'credentials' => 'The credentials field must be at least 10 characters.',
        ]);
    }

    /**
     * Test case for update function with valid request.
     */
    public function testUpdateFunctionWithValidRequest(): void
    {
        $request = $this->request;
        $pushChannel = PushChannel::factory(1)
            ->create($request)
            ->first();

        $editURL = sprintf($this->editPageURL, $pushChannel->id);
        $updateURL = sprintf($this->updatePageURL, $pushChannel->id);

        $request['name'] = 'Pusher Testing';
        $request['provider'] = 3;

        $response = $this->from($editURL)
            ->put($updateURL, $request);

        $this->assertDatabaseHas('push_channels', [
            'user_id' => 1,
            'provider' => 1,
            'name' => $request['name'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push');
        $response->assertSessionHas('flashMessage.message', 'Successfully updated.');
    }
}
