<?php

namespace Tests\Feature\Web\Push;

use App\Models\PushChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PushEditTest extends TestCase
{
    use RefreshDatabase;

    private $editPageURL = '/push/%s/edit';
    private $request = [
        'user_id' => 1,
        'provider' => 1,
        'name' => 'pusher testing',
        'credentials' => 'app_id = "1885"
        key = "26c0723"
        secret = "80e7f5"
        cluster = "ad1"',
    ];

    /**
     * Test case for edit page with invalid id.
     */
    public function testEditPageWithInvalidId(): void
    {
        $id = 1;
        $url = sprintf($this->editPageURL, $id);

        $response = $this->get($url);

        $response->assertStatus(404);
    }

    /**
     * Test case for edit page with valid id.
     */
    public function testEditPageWithValidId(): void
    {
        $request = $this->request;

        $pushChannel = PushChannel::create($request);

        $url = sprintf($this->editPageURL, $pushChannel->id);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee('Pusher');
        $response->assertSee($request['name']);
    }
}
