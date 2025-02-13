<?php

namespace Tests\Feature\Web\App;

use App\Models\App;
use App\Models\PushChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppShowTest extends TestCase
{
    use RefreshDatabase;

    private $showPageURL = '/app/%s';
    private $request = [
        'name' => 'Unit Testing',
        'description' => 'Unit Testing',
        'services' => ['push'],
    ];

    /**
     * Test case for show page with invalid id.
     */
    public function testShowPageWithInvalidId(): void
    {
        $user = User::first();

        $id = 1;

        $url = sprintf($this->showPageURL, $id);

        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(404);
    }

    /**
     * Test case for show page with valid id.
     */
    public function testShowPageWithValidId(): void
    {
        $user = User::first();

        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->request;
        $request['channels'] = ['push' => $pushChannel->id];

        $this->actingAs($user)
            ->post('/app', $request);

        $app = App::first();

        $url = sprintf($this->showPageURL, $app->id);

        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(200);
        $response->assertSee($request['name']);
        $response->assertSee($request['description']);
        $response->assertSee($app->token);
        $response->assertSee($app->refresh_token);
    }
}
