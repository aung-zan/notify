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
        'provider' => 1,
        'name' => 'pusher testing',
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

        $pushChannel = PushChannel::factory(1)
            ->create($request)
            ->first();

        $url = sprintf($this->showPageURL, $pushChannel->id);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee('Pusher');
        $response->assertSee($request['name']);
    }
}
