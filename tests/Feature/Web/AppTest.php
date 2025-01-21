<?php

namespace Tests\Feature\Web;

use App\Models\App;
use App\Models\PushChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppTest extends TestCase
{
    use RefreshDatabase;

    private function getAppTestData(): array
    {
        return [
            'name' => 'Unit Testing',
            'description' => 'Unit Testing',
            'services' => ['push'],
        ];
    }

    /**
     * Test case for index function.
     */
    public function testForIndexFunction(): void
    {
        $response = $this->get('/app');

        $response->assertStatus(200);
        $response->assertSee('App List');
    }

    /**
     * Test case for create page.
     */
    public function testCreatePage(): void
    {
        $response = $this->get('/app/create');

        $response->assertStatus(200);
        $response->assertSee('Create New App');
        $response->assertSee('Push Service');
    }

    /**
     * Test case for store function.
     */
    public function testStoreFunction(): void
    {
        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->getAppTestData();
        $request['channels'] = [$pushChannel->id];

        $response = $this->from('/app/create')
            ->post('/app', $request);

        $this->assertDatabaseHas('apps', [
            'user_id' => 1,
            'name' => $request['name'],
            'description' => $request['description'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/app');
    }

    /**
     * Test case for show function.
     */
    public function testForShowFunction(): void
    {
        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->getAppTestData();
        $request['channels'] = [$pushChannel->id];

        $this->post('/app', $request);
        $app = App::first();

        $response = $this->get('/app/' . $app->id);

        $response->assertStatus(200);
        $response->assertSee($request['name']);
        $response->assertSee($request['description']);
        $response->assertSee($app->token);
        $response->assertSee($app->refresh_token);
    }

    /**
     * Test case for edit function.
     */
    public function testForEditFunction(): void
    {
        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->getAppTestData();
        $request['channels'] = [$pushChannel->id];

        $this->post('/app', $request);
        $app = App::first();

        $response = $this->get('/app/' . $app->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee($request['name']);
        $response->assertSee($request['description']);
    }

    /**
     * Test case for update function.
     */
    public function testForUpdateFunction(): void
    {
        $pushChannel = PushChannel::factory(1)
            ->create()
            ->first();

        $request = $this->getAppTestData();
        $request['channels'] = [$pushChannel->id];

        $this->post('/app', $request);
        $app = App::first();

        $request['name'] .= 'Update';
        $request['description'] .= 'Update';

        $response = $this->from('/app/' . $app->id . '/edit')
            ->put('/app/' . $app->id . '/update', $request);

        $this->assertDatabaseHas('apps', [
            'user_id' => 1,
            'name' => $request['name'],
            'description' => $request['description'],
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/app');
    }

    /**
     * Test case for delete function.
     */
    public function testForDeleteFunction(): void
    {
        $response = $this->delete('/app/1/delete');

        $response->assertStatus(200);
        $response->assertSee('delete');
    }
}
