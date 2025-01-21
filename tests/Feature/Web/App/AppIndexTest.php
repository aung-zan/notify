<?php

namespace Tests\Feature\Web\App;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppIndexTest extends TestCase
{
    /**
     * Test case for index page.
     */
    public function testIndexPage(): void
    {
        $response = $this->get('/app');

        $response->assertStatus(200);
    }
}
