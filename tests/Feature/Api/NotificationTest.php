<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * Test case for list function.
     */
    public function testTestForListFunction(): void
    {
        $response = $this->get('/api/notification/list');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'list'
        ]);
    }

    /**
     * Test case for send function.
     */
    public function testTestForSendFunction(): void
    {
        $response = $this->get('/api/notification/send');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'sent successfully.'
        ]);
    }
}
