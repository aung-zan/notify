<?php

namespace Tests\Feature\Web\Email;

use App\Models\EmailChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailShowTest extends TestCase
{
    use RefreshDatabase;

    private $showPageURL = '/email/%s';
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

        $pushChannel = EmailChannel::create($request);

        $url = sprintf($this->showPageURL, $pushChannel->id);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertSee('Channel Details');
        $response->assertSee('Mailtrap');
        $response->assertSee($request['name']);
    }
}
