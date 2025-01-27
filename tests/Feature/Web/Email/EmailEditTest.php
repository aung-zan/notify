<?php

namespace Tests\Feature\Web\Email;

use App\Models\EmailChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailEditTest extends TestCase
{
    use RefreshDatabase;

    private $editPageURL = '/email/%s/edit';
    private $request = [
        'provider' => 1,
        'name' => 'mail testing',
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
        $emailChannel = EmailChannel::factory(1)
            ->create($request)
            ->first();

        $url = sprintf($this->editPageURL, $emailChannel->id);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee('Edit a channel');
        $response->assertSee('Mailtrap');
        $response->assertSee($request['name']);
    }
}
