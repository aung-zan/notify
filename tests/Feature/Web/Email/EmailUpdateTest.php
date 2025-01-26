<?php

namespace Tests\Feature\Web\Email;

use App\Models\EmailChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailUpdateTest extends TestCase
{
    use RefreshDatabase;

    private $editPageURL = '/email/%s/edit';
    private $updatePageURL = '/email/%s/update';
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
     * Test case for update function with invalid id.
     */
    public function testUpdateFunctionWithInvalidId(): void
    {
        $id = 1;
        $url = sprintf($this->updatePageURL, $id);
        $request = $this->request;

        $response = $this->put($url, $request);

        $response->assertStatus(302);
        $response->assertSessionHas('flashMessage.message', 'The requested resource does not exit.');
    }

    /**
     * Test case for update function with empty request.
     */
    public function testUpdateFunctionWithEmptyRequest(): void
    {
        $request = $this->request;
        $emailChannel = EmailChannel::create($request);

        $editURL = sprintf($this->editPageURL, $emailChannel->id);
        $updateURL = sprintf($this->updatePageURL, $emailChannel->id);

        $response = $this->from($editURL)
            ->put($updateURL, []);

        $response->assertStatus(302);
        $response->assertRedirect($editURL);
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
        ]);
    }

    /**
     * Test case for update function with invalid request.
     */
    public function testUpdateFunctionWithInvalidRequest(): void
    {
        $request = $this->request;
        $emailChannel = EmailChannel::create($request);

        $editURL = sprintf($this->editPageURL, $emailChannel->id);
        $updateURL = sprintf($this->updatePageURL, $emailChannel->id);

        $request['name'] = '';
        $request['credentials'] = '';

        $response = $this->from($editURL)
            ->put($updateURL, $request);

        $response->assertStatus(302);
        $response->assertRedirect($editURL);
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.',
            'credentials' => 'The credentials field must be at least 10 characters.',
        ]);
    }

    /**
     * Test case for update function with valid request.
     */
    public function testUpdateFunctionWithValidRequest(): void
    {
        $request = $this->request;
        $emailChannel = EmailChannel::create($request);

        $editURL = sprintf($this->editPageURL, $emailChannel->id);
        $updateURL = sprintf($this->updatePageURL, $emailChannel->id);

        $request['name'] = 'Email Testing';
        $request['user_id'] = 3;
        $request['provider'] = 3;

        $response = $this->from($editURL)
            ->put($updateURL, $request);

        $this->assertDatabaseHas('email_channels', [
            'user_id' => 1,
            'provider' => 1,
            'name' => $request['name'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/email');
    }
}
