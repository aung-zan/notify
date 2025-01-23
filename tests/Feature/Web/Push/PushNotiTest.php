<?php

namespace Tests\Feature\Web\Push;

use App\Models\PushChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PushNotiTest extends TestCase
{
    use RefreshDatabase;

    private $notiPageURL = '/push/%s/test';
    private $notiRequest = [
        'message' => 'Hello Notifcation Testing.'
    ];
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
     * A basic feature test example.
     */
    public function testNotificationTestPageWithInvalidId(): void
    {
        $url = sprintf($this->notiPageURL, 1);

        $response = $this->get($url);

        $response->assertStatus(404);
    }

    public function testNotificationTestPageWithValidId(): void
    {
        $pushChannel = PushChannel::create($this->request);

        $url = sprintf($this->notiPageURL, $pushChannel->id);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee('Push Channels');
        $response->assertSee('Test Pusher Channel');
        $response->assertSee('pusher testing');
    }

    public function testNotificationTestFunctionWithEmptyRequest(): void
    {
        $pushChannel = PushChannel::create($this->request);

        $url = sprintf($this->notiPageURL, $pushChannel->id);

        $response = $this->postJson($url, []);

        $response->assertHeader('Content-Type', 'application/json');
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'No data provided.'
        ]);
    }

    public function testNotificationTestFunctionWithValidRequest(): void
    {
        Log::spy();

        Log::shouldReceive('info')
            ->withArgs(function ($message) {
                return str_contains($message, $this->notiRequest['message']);
            })
            ->once();

        $pushChannel = PushChannel::create($this->request);
        $url = sprintf($this->notiPageURL, $pushChannel->id);

        $response = $this->postJson($url, $this->notiRequest);

        $this->assertEquals('log', config('broadcasting.default'));

        $response->assertHeader('Content-Type', 'application/json');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Push notification sent successfully.'
        ]);
    }
}
