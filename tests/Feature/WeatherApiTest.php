<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeatherApiTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_get_current_weather_authenticated()
    {
        $user = User::factory()->create([
            'location_preference' => 'Boston'
        ]);
        
        $response = $this->actingAs($user)
                        ->getJson('/api/v1/weather/current');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'location',
                    'current'
                ]);
    }
    
    public function test_get_current_weather_with_location_param()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                        ->getJson('/api/v1/weather/current?location=Paris');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'location',
                    'current'
                ]);
    }
}