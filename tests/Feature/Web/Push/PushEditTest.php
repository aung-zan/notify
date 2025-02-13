<?php

namespace Tests\Feature\Web\Push;

use App\Models\PushChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PushEditTest extends TestCase
{
    use RefreshDatabase;

    private $editPageURL = '/push/%s/edit';
    private $request = [
        'provider' => 1,
        'name' => 'pusher testing',
    ];

    /**
     * Test case for edit page with invalid id.
     */
    public function testEditPageWithInvalidId(): void
    {
        $user = User::first();

        $id = 1;
        $url = sprintf($this->editPageURL, $id);

        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(404);
    }

    /**
     * Test case for edit page with valid id.
     */
    public function testEditPageWithValidId(): void
    {
        $user = User::first();

        $request = $this->request;

        $pushChannel = PushChannel::factory(1)
            ->create($request)
            ->first();

        $url = sprintf($this->editPageURL, $pushChannel->id);

        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(200);
        $response->assertSee('Pusher');
        $response->assertSee($request['name']);
    }
}
