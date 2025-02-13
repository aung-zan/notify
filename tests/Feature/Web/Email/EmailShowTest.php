<?php

namespace Tests\Feature\Web\Email;

use App\Models\EmailChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailShowTest extends TestCase
{
    use RefreshDatabase;

    private $showPageURL = '/email/%s';
    private $request = [
        'provider' => 1,
        'name' => 'mail testing',
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

        $request = $this->request;
        $emailChannel = EmailChannel::factory(1)
            ->create($request)
            ->first();

        $url = sprintf($this->showPageURL, $emailChannel->id);

        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(200);
        $response->assertSee('Channel Details');
        $response->assertSee('Mailtrap');
        $response->assertSee($request['name']);
    }
}
