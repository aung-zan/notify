<?php

namespace Tests\Feature\Web\Push;

use App\Enums\PushProviders;
use App\Models\PushChannel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PushIndexTest extends TestCase
{
    use RefreshDatabase;

    private $contentType = 'application/json';
    private $indexPageURL = '/push';
    private $getDataURL = '/push/data';
    private $request = [
        'user_id' => 1,
        'credentials' => 'app_id = "1885"
        key = "26c0723"
        secret = "80e7f5"
        cluster = "ad1"',
    ];
    private $datatableRequest = [
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

    /**
     * test case for index page.
     */
    public function testIndexPage(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)
            ->get($this->indexPageURL);

        $response->assertStatus(200);
        $response->assertSee('Push Channels');
        $response->assertSee('Channel List');
    }

    /**
     * Test case for getData function with empty request.
     */
    public function testGetDataFunctionWithEmptyRequest(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)
            ->postJson($this->getDataURL, []);

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

        $this->createPushData($user);

        $datatableRequest = $this->datatableRequest;

        $response = $this->actingAs($user)
            ->postJson($this->getDataURL, $datatableRequest);

        $response->assertStatus(200);
        $response->assertHeader('Content-type', $this->contentType);

        $data = json_decode($response->content())?->data;
        $this->assertCount(2, $data);
    }

    /**
     * Test case for getData function with search request.
     */
    public function testGetDataFunctionWithSearchRequest(): void
    {
        $user = User::first();

        $this->createPushData($user);

        $datatableRequest = $this->datatableRequest;
        $datatableRequest['search']['value'] = 'pusher';

        $response = $this->actingAs($user)
            ->postJson($this->getDataURL, $datatableRequest);

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

        $expectedResult = ['pusher testing', 'firebase testing'];

        $this->createPushData($user);

        $datatableRequest = $this->datatableRequest;
        $datatableRequest['order'][0]['dir'] = 'desc';

        $response = $this->actingAs($user)
            ->postJson($this->getDataURL, $datatableRequest);

        $response->assertStatus(200);
        $response->assertHeader('Content-type', $this->contentType);

        $data = json_decode($response->content())?->data;
        $result = array_column($data, 'name');

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * preapre and create the push data.
     *
     * @param User $user
     * @return void
     */
    private function createPushData(User $user): void
    {
        $data = PushProviders::getAll();

        foreach ($data as $text => $value) {
            $request = $this->actingAs($user)
                ->updateRequestData($text, $value);

            PushChannel::create($request);
        }
    }

    /**
     * Update the request data.
     *
     * @param string $text
     * @param int $value
     * @return array
     */
    private function updateRequestData(string $text, int $value): array
    {
        $request = $this->request;
        $request['provider'] = $value;
        $request['name'] = strtolower($text) . ' testing';

        return $request;
    }
}
