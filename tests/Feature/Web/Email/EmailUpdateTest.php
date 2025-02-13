<?php

namespace Tests\Feature\Web\Email;

use App\Models\EmailChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailUpdateTest extends TestCase
{
    use RefreshDatabase;

    private $editPageURL = '/email/%s/edit';
    private $updatePageURL = '/email/%s/update';
    private $request = [
        'provider' => 1,
        'name' => 'mail testing',
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

        $response->assertStatus(302);
        $response->assertSessionHas('flashMessage.message', 'The requested resource does not exit.');
    }

    /**
     * Test case for update function with empty request.
     */
    public function testUpdateFunctionWithEmptyRequest(): void
    {
        $user = User::first();

        $request = $this->request;
        $emailChannel = EmailChannel::factory(1)
            ->create($request)
            ->first();

        $editURL = sprintf($this->editPageURL, $emailChannel->id);
        $updateURL = sprintf($this->updatePageURL, $emailChannel->id);

        $response = $this->actingAs($user)
            ->from($editURL)
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
        $user = User::first();

        $request = $this->request;
        $emailChannel = EmailChannel::factory(1)
            ->create($request)
            ->first();

        $editURL = sprintf($this->editPageURL, $emailChannel->id);
        $updateURL = sprintf($this->updatePageURL, $emailChannel->id);

        $request['name'] = '';
        $request['credentials'] = '';

        $response = $this->actingAs($user)
            ->from($editURL)
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
        $user = User::first();

        $request = $this->request;
        $emailChannel = EmailChannel::factory(1)
            ->create($request)
            ->first();

        $editURL = sprintf($this->editPageURL, $emailChannel->id);
        $updateURL = sprintf($this->updatePageURL, $emailChannel->id);

        $request['name'] = 'Email Testing';
        $request['user_id'] = 3;
        $request['provider'] = 3;

        $response = $this->actingAs($user)
            ->from($editURL)
            ->put($updateURL, $request);

        $this->assertDatabaseHas('email_channels', [
            'user_id' => 1,
            'provider' => 1,
            'name' => $request['name'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/email');
        $response->assertSessionHas('flashMessage.message', 'Successfully updated.');
    }
}
