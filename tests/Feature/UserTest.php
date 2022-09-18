<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
    * Get user profile details
    *
    * @return void
    */
    public function test_logged_in_user_profile_is_accessible()
    {
        
        $baseUrl = config('app.url');

        $user = User::factory()->create();

        $token=JWTAuth::fromUser($user);

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */        
        $response = $this->actingAs($user)
            ->json('GET', $baseUrl . '/api/user')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status', 'data'
            ]);
    }
}
