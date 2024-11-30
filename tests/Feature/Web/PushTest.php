<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PushTest extends TestCase
{
    /**
     * Test case for index function.
     */
    public function testForIndexFunction(): void
    {
        $response = $this->get('/push');

        $response->assertStatus(200);
        $response->assertSee('index');
    }

    /**
     * Test case for create function.
     */
    public function testForCreateFunction(): void
    {
        $response = $this->get('/push/create');

        $response->assertStatus(200);
        $response->assertSee('create');
    }

    /**
     * Test case for store function.
     */
    public function testForStoreFunction(): void
    {
        $request = [];
        $response = $this->post('/push', $request);

        $response->assertStatus(200);
        $response->assertSee('store');
    }

    /**
     * Test case for show function.
     */
    public function testForShowFunction(): void
    {
        $response = $this->get('/push/1');

        $response->assertStatus(200);
        $response->assertSee('show');
    }

    /**
     * Test case for edit function.
     */
    public function testForEditFunction(): void
    {
        $response = $this->get('/push/1/edit');

        $response->assertStatus(200);
        $response->assertSee('edit');
    }

    /**
     * Test case for update function.
     */
    public function testForUpdateFunction(): void
    {
        $request = [];

        $response = $this->patch('/push/1/update', $request);

        $response->assertStatus(200);
        $response->assertSee('update');
    }
}
