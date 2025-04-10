<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MissingRouteTest extends TestCase
{
    /**
     * A basic feature test missing_api_routes.
     */
    public function test_returns_json_for_missing_api_routes()
    {
        $response = $this->getJson('/non-existent-route');
        
        $response->assertStatus(404)
            ->assertJsonStructure([
                'status',
                'code',
                'message',
                'request' => [
                    'url',
                    'method'
                ],
                'suggestions',
            ]);
    }
    
}
