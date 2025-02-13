<?php

namespace Tests\Feature\Web\App;

use App\Models\PushChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppIndexTest extends TestCase
{
    use RefreshDatabase;

    private $contentType = 'application/json';
    private $getDatURL = '/app/data';
    private $request = [
        'name' => 'Unit Testing',
        'description' => 'Unit Testing',
        'services' => ['push'],
    ];
    private $datatableRequest = [
        'draw' => '1',
        'columns' => [
            ['data' => 'name'],
            ['data' => 'service_display'],
            ['data' => 'channel_display'],
            ['data' => 'created_at'],
            ['data' => 'updated_at'],
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

    /**
     * Test case for index page.
     */
    public function testIndexPage(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)
            ->get('/app');

        $response->assertStatus(200);
    }

    /**
     * Test case for getData function with empty request.
     */
    public function testGetDataFunctionWithEmptyRequest(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)
            ->postJson($this->getDatURL, []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'draw' => 'The draw field is required.',
            'columns' => 'The columns field is required.',
            'start' => 'The start field is required.',
            'length' => 'The length field is required.',
            'search' => 'The search field is required.',
        ]);
    }

    /**
     * Test case for getData function with valid request.
     */
    public function testGetDataFunctionWithValidRequest(): void
    {
        $user = User::first();

        $this->createAppData([''], $user);

        $datatableRequest = $this->datatableRequest;

        $response = $this->actingAs($user)
            ->postJson($this->getDatURL, $datatableRequest);

        $response->assertStatus(200);
        $response->assertHeader('Content-type', $this->contentType);

        $data = json_decode($response->content())?->data;
        $this->assertCount(1, $data);
    }

    /**
     * Test case for getData function with search request.
     */
    public function testGetDataFunctionWithSearchRequest(): void
    {
        $user = User::first();

        $appData = [' One', ' Two'];

        $this->createAppData($appData, $user);

        $datatableRequest = $this->datatableRequest;
        $datatableRequest['search']['value'] = 'One';

        $response = $this->actingAs($user)
            ->postJson($this->getDatURL, $datatableRequest);

        $response->assertStatus(200);
        $response->assertHeader('Content-type', $this->contentType);

        $data = json_decode($response->content())?->data;
        $this->assertCount(1, $data);
    }

    /**
     * Test case for getData function with order request.
     */
    public function testGetDataFunctionWithOrderRequest(): void
    {
        $user = User::first();

        $appData = [' One', ' Two'];
        $expectedResult = ['Unit Testing Two', 'Unit Testing One'];

        $this->createAppData($appData, $user);

        $datatableRequest = $this->datatableRequest;
        $datatableRequest['order'][0]['dir'] = 'desc';

        $response = $this->actingAs($user)
            ->postJson($this->getDatURL, $datatableRequest);

        $response->assertStatus(200);
        $response->assertHeader('Content-type', $this->contentType);

        $data = json_decode($response->content())?->data;
        $result = array_column($data, 'name');

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * preapre and create the app data.
     *
     * @param array $data
     * @param User $user
     * @return void
     */
    private function createAppData(array $data, User $user): void
    {
        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        foreach ($data as $text) {
            $request = $this->updateRequest($text, $pushChannel->id);

            $this->actingAs($user)
                ->post('/app', $request);
        }
    }

    /**
     * Update the request data.
     *
     * @param string $text
     * @param int $channelId
     * @return array
     */
    private function updateRequest(string $text, int $channelId): array
    {
        $request = $this->request;
        $request['name'] .= $text;
        $request['description'] .= $text;
        $request['channels'] = ['push' => $channelId];

        return $request;
    }
}
