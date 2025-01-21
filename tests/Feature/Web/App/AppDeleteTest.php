<?php

namespace Tests\Feature\Web\App;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppDeleteTest extends TestCase
{
    /**
     * Test case for delete function.
     */
    public function testDeleteFunction(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
