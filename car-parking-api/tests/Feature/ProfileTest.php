<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetTheirProfile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/profile');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['name', 'email']])
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['name' => $user->getAttribute('name')]);
    }

    public function testUserCanUpdateNameAndEmail(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/v1/profile', [
            'name'  => 'John Updated',
            'email' => 'john_updated@example.com',
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['data' => ['name', 'email']])
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['name' => 'John Updated']);

        $this->assertDatabaseHas('users', [
            'name'  => 'John Updated',
            'email' => 'john_updated@example.com',
        ]);
    }

    public function testUserCanChangePassword(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/v1/password', [
            'current_password'      => 'password',
            'password'              => 'testing123',
            'password_confirmation' => 'testing123',
        ]);

        $response->assertStatus(202);
    }
}
