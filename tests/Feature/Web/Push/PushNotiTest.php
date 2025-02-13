<?php

namespace Tests\Feature\Web\Push;

use App\Models\PushChannel;
use App\Models\User;
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
        'provider' => 1,
        'name' => 'pusher testing',
    ];

    /**
     * A basic feature test example.
     */
    public function testNotificationTestPageWithInvalidId(): void
    {
        $user = User::first();

        $url = sprintf($this->notiPageURL, 1);

        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(404);
    }

    public function testNotificationTestPageWithValidId(): void
    {
        $user = User::first();

        $pushChannel = PushChannel::factory(1)
            ->create($this->request)
            ->first();

        $url = sprintf($this->notiPageURL, $pushChannel->id);

        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(200);
        $response->assertSee('Push Channels');
        $response->assertSee('Test Pusher Channel');
        $response->assertSee('pusher testing');
    }

    public function testNotificationTestFunctionWithEmptyRequest(): void
    {
        $user = User::first();

        $pushChannel = PushChannel::factory(1)
            ->create($this->request)
            ->first();

        $url = sprintf($this->notiPageURL, $pushChannel->id);

        $response = $this->actingAs($user)
            ->postJson($url, []);

        $response->assertHeader('Content-Type', 'application/json');
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'No data provided.'
        ]);
    }

    public function testNotificationTestFunctionWithValidRequest(): void
    {
        $user = User::first();

        Log::spy();

        Log::shouldReceive('info')
            ->withArgs(function ($message) {
                return str_contains($message, $this->notiRequest['message']);
            })
            ->once();

        $pushChannel = PushChannel::factory(1)
            ->create($this->request)
            ->first();
        $url = sprintf($this->notiPageURL, $pushChannel->id);

        $response = $this->actingAs($user)
            ->postJson($url, $this->notiRequest);

        $this->assertEquals('log', config('broadcasting.default'));

        $response->assertHeader('Content-Type', 'application/json');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Push notification sent successfully.'
        ]);
    }
}
