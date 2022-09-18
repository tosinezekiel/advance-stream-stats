<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
    * Invalid credentials can't login.
    *
    * @return void
    */
    public function test_invalid_credentials_cannot_login()
    {
        
        $baseUrl = config('app.url');

        $user = User::factory()->create();

        $this->json('POST', $baseUrl . '/', [
            'email' => 'unknown-email@test.com',
            'password' => 'password'
        ])->assertStatus(405);

        $this->json('POST', $baseUrl . '/', [
            'email' => $user->email,
            'password' => 'wrong_password'
        ])->assertStatus(405);
    }

    /**
    * Login as default API user and get token back.
    *
    * @return void
    */
    public function test_existing_user_can_login()
    {
        
        $baseUrl = config('app.url');

        $user = User::factory()->create();

        $response = $this->json('POST', $baseUrl . '/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status', 'authorization'
            ]);
    }

    /**
    * User can logout
    *
    * @return void
    */
    public function test_logged_in_user_can_logout()
    {
        
        $baseUrl = config('app.url');

        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user)->json('POST', $baseUrl . '/api/auth/logout');

        $response->assertStatus(200)
            ->assertExactJson([
                'message' => 'Successfully logged out.',
                'status' => 'Success.'
            ]);
    }
}
