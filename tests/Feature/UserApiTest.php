<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserApiTest extends TestCase
{

    public function test_user_can_update_profile()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->putJson('/api/v1/user/profile', [
                'name' => 'New Name',
                'email' => 'new@example.com'
            ]);
        
        $response->assertStatus(200)
            ->assertJson(['message' => 'Profile updated successfully']);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com'
        ]);
    }
}
