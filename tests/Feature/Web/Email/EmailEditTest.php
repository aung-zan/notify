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
        'user_id' => 1,
        'provider' => 1,
        'name' => 'mail testing',
        'credentials' => 'MAIL_MAILER=smtp
        MAIL_HOST=sandbox.smtp.mailtrap.io
        MAIL_PORT=2525
        MAIL_USERNAME=1a601ae54273fa
        MAIL_PASSWORD=ec32bbd0f06979',
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

        $emailChannel = EmailChannel::create($request);

        $url = sprintf($this->editPageURL, $emailChannel->id);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee('Edit a channel');
        $response->assertSee('Mailtrap');
        $response->assertSee($request['name']);
    }
}
