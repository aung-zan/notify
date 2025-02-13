<?php

namespace Tests\Feature\Web\App;

use App\Models\App;
use App\Models\PushChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppEditTest extends TestCase
{
    use RefreshDatabase;

    private $editPageURL = '/app/%s/edit';
    private $request = [
        'name' => 'Unit Testing',
        'description' => 'Unit Testing',
        'services' => ['push'],
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

        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->request;
        $request['channels'] = ['push' => $pushChannel->id];

        $this->actingAs($user)
            ->post('/app', $request);

        $app = App::first();

        $url = sprintf($this->editPageURL, $app->id);

        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(200);
        $response->assertSee($request['name']);
        $response->assertSee($request['description']);
    }
}
