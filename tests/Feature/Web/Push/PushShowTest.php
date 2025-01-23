<?php

namespace Tests\Feature\Web\Push;

use App\Models\PushChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PushShowTest extends TestCase
{
    use RefreshDatabase;

    private $showPageURL = '/push/%s';
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
     * Test case for show page with invalid id.
     */
    public function testShowPageWithInvalidId(): void
    {
        $id = 1;

        $url = sprintf($this->showPageURL, $id);

        $response = $this->get($url);

        $response->assertStatus(404);
    }

    /**
     * Test case for show page with valid id.
     */
    public function testShowPageWithValidId(): void
    {
        $request = $this->request;

        $pushChannel = PushChannel::create($request);

        $url = sprintf($this->showPageURL, $pushChannel->id);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee('Pusher');
        $response->assertSee($request['name']);
    }
}
