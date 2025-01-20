<?php

namespace Tests\Feature\Web;

use App\Models\PushChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PushTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->datatableRequest = [
            'draw' => '1',
            'columns' => [
                ['data' => 'name'],
                ['data' => 'provider'],
                ['data' => 'created_at'],
                ['data' => 'function'],
            ],
            'order' => [
                [
                    'column' => '0',
                    'dir' => 'asc',
                    'name' => null,
                ],
            ],
            'start' => '0',
            'length' => '10',
            'search' => [
                'value' => null,
                'regex' => 'false',
            ],
        ];

        $this->request = [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing',
            'credentials' => 'app_id = "1885"
            key = "26c0723"
            secret = "80e7f5"
            cluster = "ad1"',
        ];

        $this->requestTypeTwo = [
            'user_id' => 1,
            'provider' => 2,
            'name' => 'firebase testing',
            'credentials' => 'app_id = "1885"
            key = "26c0723"
            secret = "80e7f5"
            cluster = "ad1"',
        ];
    }

    /**
     * Test case for index page.
     */
    public function testIndexPage(): void
    {
        $response = $this->get('/push');

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Channel List');
    }

    /**
     * Test case for getData function with empty request.
     */
    public function testGetDataEmptyRequest(): void
    {
        $response = $this->postJson('/push/data', []);
        $response->assertStatus(422);
    }

    /**
     * Test case for getData function with invalid request.
     */
    public function testGetDataInvalidRequest(): void
    {
        $response = $this->postJson('/push/data', [
            'columns' => [
                ['data' => 'name']
            ]
        ]);
        $response->assertStatus(422);
    }

    /**
     * Test case for getData function with valid request.
     */
    public function testGetDataValidRequest(): void
    {
        PushChannel::create($this->request);
        PushChannel::create($this->requestTypeTwo);

        $response = $this->postJson('/push/data', $this->datatableRequest);

        $response->assertHeader('Content-type', 'application/json');
        $response->assertStatus(200);

        $data = json_decode($response->content())?->data;
        $this->assertCount(2, $data);
    }

    /**
     * Test case for getData function with valid and search request.
     */
    public function testGetDataSearch(): void
    {
        PushChannel::create($this->request);
        PushChannel::create($this->requestTypeTwo);

        $this->datatableRequest['search']['value'] = 'pusher';

        $response = $this->postJson('/push/data', $this->datatableRequest);

        $response->assertHeader('Content-type', 'application/json');
        $response->assertStatus(200);

        $data = json_decode($response->content())?->data;
        $this->assertCount(1, $data);
    }

    /**
     * Test case for createcreate page.
     */
    public function testCreatePage(): void
    {
        $response = $this->get('/push/create');

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Create New Channel');
    }

    /**
     * Test case for store function with empty request.
     */
    public function testStoreEmptyRequest(): void
    {
        $request = [];
        $response = $this->from('/push/create')
            ->post('/push', $request);

        $response->assertStatus(302);
        $response->assertRedirect('/push/create');
        $response->assertSessionHasErrors([
            'provider' => 'The provider field is required.',
            'name' => 'The name field is required.',
            'credentials' => 'The credentials field is required.',
        ]);
    }

    /**
     * Test case for store function with invalid request.
     */
    public function testStoreInvalidRequest(): void
    {
        $request = [
            'provider' => 100,
            'name' => 't',
            'credentials' => 't',
        ];
        $response = $this->from('/push/create')
            ->post('/push', $request);

        $response->assertStatus(302);
        $response->assertRedirect('/push/create');

        $response->assertSessionHasErrors([
            'provider' => 'The selected provider is invalid.',
            'credentials' => 'The credentials field must be at least 10 characters.',
        ]);
    }

    /**
     * Test case for store function with valid request.
     */
    public function testStoreValidRequest(): void
    {
        $response = $this->from('/push/create')
            ->post('/push', $this->request);

        $this->assertDatabaseHas('push_channels', [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push');
    }

    /**
     * Test case for show page with invalid id.
     */
    public function testShowPageInvalidId(): void
    {
        $response = $this->get('/push/100');

        $response->assertStatus(404);
    }

    /**
     * Test case for show page with valid id.
     */
    public function testShowPageValidId(): void
    {
        $record = PushChannel::create($this->request);

        $response = $this->get('/push/' . $record->id);

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Channel Details');
        $response->assertSee('pusher testing');
    }

    /**
     * Test case for edit page with invalid id.
     */
    public function testEditPageInvalidId(): void
    {
        $response = $this->get('/push/100/edit');

        $response->assertStatus(404);
    }

    /**
     * Test case for edit page with valid id.
     */
    public function testEditPageValidId(): void
    {
        $record = PushChannel::create($this->request);

        $response = $this->get('/push/' . $record->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Edit a channel');
    }

    /**
     * Test case for update function with empty request.
     */
    public function testUpdateEmptyRequest(): void
    {
        $request = [];
        $record = PushChannel::create($this->request);

        $response = $this->from('/push/' . $record->id . '/edit')
            ->patch('/push/' . $record->id . '/update', $request);

        $this->assertDatabaseHas('push_channels', [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push/' . $record->id . '/edit');
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ]);
    }

    /**
     * Test case for update function with invalid request.
     */
    public function testUpdateInvalidRequest(): void
    {
        $request = [
            'name' => 'testing 123',
            'credentials' => '',
        ];

        $record = PushChannel::create($this->request);

        $response = $this->from('/push/' . $record->id . '/edit')
            ->patch('/push/' . $record->id . '/update', $request);

        $this->assertDatabaseHas('push_channels', [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push/' . $record->id . '/edit');
        $response->assertSessionHasErrors([
            'credentials' => 'The credentials field must be at least 10 characters.'
        ]);
    }

    /**
     * Test case for update function with valid request.
     */
    public function testUpdateValidRequest(): void
    {
        $request = [
            'user_id' => 3,
            'provider' => 3,
            'name' => 'pusher testing 1',
        ];

        $record = PushChannel::create($this->request);

        $response = $this->from('/push/' . $record->id . '/edit')
            ->patch('/push/' . $record->id . '/update', $request);

        $this->assertDatabaseHas('push_channels', [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing 1',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push');
    }

    /**
     * Test case for update function with valid request credentials.
     */
    public function testUpdateValidRequestCredentials(): void
    {
        $request = [
            'name' => 'pusher testing 1',
            'credentials' => 'app_id = "1885"
            key = "26c0723"
            secret = "80e7f5"
            cluster = "ad1"',
        ];

        $record = PushChannel::create($this->request);

        $response = $this->from('/push/' . $record->id . '/edit')
            ->patch('/push/' . $record->id . '/update', $request);

        $this->assertDatabaseHas('push_channels', [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing 1',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push');
    }

    /**
     * Test case for test notification page with invalid id.
     */
    public function testTestNotificationPageInvalidId(): void
    {
        $response = $this->get('/push/100/test');

        $response->assertStatus(404);
    }

    /**
     * Test case for test notification page with valid id.
     */
    public function testTestNotificationPageValidId(): void
    {
        $record = PushChannel::create($this->request);

        $response = $this->get('/push/' . $record->id . '/test');

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Test Pusher Channel');
        $response->assertSee('pusher testing');
    }

    /**
     * Test case for test notification function with empty request.
     */
    public function testTestNotificationEmptyRequest(): void
    {
        $record = PushChannel::create($this->request);

        $response = $this->post('/push/' . $record->id . '/test', []);

        $response->assertHeader('Content-Type', 'application/json');
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'No data provided.'
        ]);
    }

    /**
     * Test case for test notification function with valid request.
     */
    public function testTestNotificationValidRequest(): void
    {
        Log::spy();

        $request = [
            'message' => 'Hello Notifcation Testing.'
        ];

        Log::shouldReceive('info')
            ->withArgs(function ($message) {
                return str_contains($message, 'Hello Notifcation Testing.');
            })
            ->once();

        $record = PushChannel::create($this->request);
        $response = $this->post('/push/' . $record->id . '/test', $request);

        $this->assertEquals('log', config('broadcasting.default'));

        $response->assertHeader('Content-Type', 'application/json');
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Push notification sent successfully.'
        ]);
    }
}
