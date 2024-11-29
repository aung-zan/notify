<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppTest extends TestCase
{
    /**
     * Test case for index function.
     */
    public function testForIndexFunction(): void
    {
        $response = $this->get('/app');

        $response->assertStatus(200);
        $response->assertSee('index');
    }

    /**
     * Test case for show function.
     */
    public function testForShowFunction(): void
    {
        $response = $this->get('/app/1');

        $response->assertStatus(200);
        $response->assertSee('show');
    }

    /**
     * Test case for edit function.
     */
    public function testForEditFunction(): void
    {
        $response = $this->get('/app/1/edit');

        $response->assertStatus(200);
        $response->assertSee('edit');
    }

    /**
     * Test case for update function.
     */
    public function testForUpdateFunction(): void
    {
        $request = [];

        $response = $this->patch('/app/1/update', $request);

        $response->assertStatus(200);
        $response->assertSee('update');
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
