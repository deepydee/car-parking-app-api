<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLoginWithCorrectCredentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => $user->getAttribute('email'),
            'password' => 'password',
        ]);

        $response->assertStatus(201);
    }

    public function testUserCannotLoginWithIncorrectCredentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => $user->getAttribute('email'),
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(422);
    }

    public function testUserCanRegisterWithCorrectCredentials(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name'  => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function testUserCannotRegisterWithIncorrectCredentials(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password',
            'password_confirmation' => 'wrong_password',
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'name'  => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }
}
