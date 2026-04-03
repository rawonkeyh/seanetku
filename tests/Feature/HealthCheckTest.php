<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    /**
     * Test health check endpoint.
     */
    public function test_health_check_endpoint(): void
    {
        $response = $this->get('/api/v1/health');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'ok',
        ]);
    }

    /**
     * Test packages endpoint.
     */
    public function test_packages_endpoint(): void
    {
        $response = $this->get('/api/v1/packages');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
        ]);
    }
}
