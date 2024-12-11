<?php

namespace Tests\Feature\Web;

use App\Models\Push;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
     * Test case for index function.
     */
    public function testIndexFunction(): void
    {
        $response = $this->get('/push');

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Channel List');
    }

    /**
     * Test case for getData function.
     */
    public function testGetDataFunctionWithEmptyRequest(): void
    {
        $response = $this->postJson('/push/data', []);
        $response->assertStatus(422);
    }

    /**
     * Test case for getData function.
     */
    public function testGetDataFunctionWithWrongRequest(): void
    {
        $response = $this->postJson('/push/data', [
            'columns' => [
                ['data' => 'name']
            ]
        ]);
        $response->assertStatus(422);
    }

    /**
     * Test case for getData function.
     */
    public function testGetDataFunctionWithRightRequest(): void
    {
        Push::create($this->request);
        Push::create($this->requestTypeTwo);

        $response = $this->postJson('/push/data', $this->datatableRequest);

        $response->assertHeader('Content-type', 'application/json');
        $response->assertStatus(200);

        $data = json_decode($response->content())?->data;
        $this->assertCount(2, $data);
    }

    /**
     * Test case for getData function.
     */
    public function testGetDataFunctionWithRightRequestAndSearch(): void
    {
        Push::create($this->request);
        Push::create($this->requestTypeTwo);

        $this->datatableRequest['search']['value'] = 'pusher';

        $response = $this->postJson('/push/data', $this->datatableRequest);

        $response->assertHeader('Content-type', 'application/json');
        $response->assertStatus(200);

        $data = json_decode($response->content())?->data;
        $this->assertCount(1, $data);
    }

    /**
     * Test case for create function.
     */
    public function testCreateFunction(): void
    {
        $response = $this->get('/push/create');

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Create New Channel');
    }

    /**
     * Test case for store function.
     */
    public function testStoreFunctionWithEmptyData(): void
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
     * Test case for store function.
     */
    public function testStoreFunctionWithWrongData(): void
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
     * Test case for store function.
     */
    public function testStoreFunctionWithRightData(): void
    {
        $response = $this->from('/push/create')
            ->post('/push', $this->request);

        $this->assertDatabaseHas('pushes', [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push');
    }

    /**
     * Test case for show function.
     */
    public function testShowFunctionWithWrongId(): void
    {
        $response = $this->get('/push/100');

        $response->assertStatus(404);
    }

    /**
     * Test case for show function.
     */
    public function testShowFunctionWithRightId(): void
    {
        $record = Push::create($this->request);

        $response = $this->get('/push/' . $record->id);

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Channel Details');
        $response->assertSee('pusher testing');
    }

    /**
     * Test case for edit function.
     */
    public function testEditFunctionWithWrongId(): void
    {
        $response = $this->get('/push/100/edit');

        $response->assertStatus(404);
    }

    /**
     * Test case for edit function.
     */
    public function testEditFunctionWithRightId(): void
    {
        $record = Push::create($this->request);

        $response = $this->get('/push/' . $record->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Push Notification');
        $response->assertSee('Edit a channel');
    }

    /**
     * Test case for update function.
     */
    public function testUpdateFunctionWithEmptyData(): void
    {
        $request = [];
        $record = Push::create($this->request);

        $response = $this->from('/push/' . $record->id . '/edit')
            ->patch('/push/' . $record->id . '/update', $request);

        $this->assertDatabaseHas('pushes', [
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
     * Test case for update function.
     */
    public function testUpdateFunctionWithWrongData(): void
    {
        $request = [
            'name' => 'testing 123',
            'credentials' => '',
        ];

        $record = Push::create($this->request);

        $response = $this->from('/push/' . $record->id . '/edit')
            ->patch('/push/' . $record->id . '/update', $request);

        $this->assertDatabaseHas('pushes', [
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
     * Test case for update function.
     */
    public function testUpdateFunctionWithRightData1(): void
    {
        $request = [
            'user_id' => 3,
            'provider' => 3,
            'name' => 'pusher testing 1',
        ];

        $record = Push::create($this->request);

        $response = $this->from('/push/' . $record->id . '/edit')
            ->patch('/push/' . $record->id . '/update', $request);

        $this->assertDatabaseHas('pushes', [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing 1',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push');
    }

    /**
     * Test case for update function.
     */
    public function testUpdateFunctionWithRightData2(): void
    {
        $request = [
            'name' => 'pusher testing 1',
            'credentials' => 'app_id = "1885"
            key = "26c0723"
            secret = "80e7f5"
            cluster = "ad1"',
        ];

        $record = Push::create($this->request);

        $response = $this->from('/push/' . $record->id . '/edit')
            ->patch('/push/' . $record->id . '/update', $request);

        $this->assertDatabaseHas('pushes', [
            'user_id' => 1,
            'provider' => 1,
            'name' => 'pusher testing 1',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/push');
    }
}
