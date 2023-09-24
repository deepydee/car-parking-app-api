<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanGetTheirOwnVehicles(): void
    {
        $john = User::factory()->create();
        $vehicleForJohn = Vehicle::factory()->create([
            'user_id' => $john->getAttribute('id')
        ]);

        $adam = User::factory()->create();
        $vehicleForAdam = Vehicle::factory()->create([
            'user_id' => $adam->getAttribute('id')
        ]);

        $response = $this->actingAs($john)->getJson('/api/v1/vehicles');

        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.plate_number', $vehicleForJohn->getAttribute('plate_number'))
            ->assertJsonMissing($vehicleForAdam->toArray());
    }

    public function testUserCanCreateVehicle(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/vehicles', [
            'plate_number' => 'AAA111',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plate_number'],
            ])
            ->assertJsonPath('data.plate_number', 'AAA111');

        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'AAA111',
        ]);
    }

    public function testUserCanUpdateTheirVehicle(): void
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->getAttribute('id')]);

        $response = $this->actingAs($user)->putJson('/api/v1/vehicles/' . $vehicle->getAttribute('id'), [
            'plate_number' => 'AAA123',
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure(['data' => ['plate_number']])
            ->assertJsonPath('data.plate_number', 'AAA123');

        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'AAA123',
        ]);
    }

    public function testUserCanDeleteTheirVehicle(): void
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user->getAttribute('id')]);

        $response = $this->actingAs($user)->deleteJson('/api/v1/vehicles/' . $vehicle->getAttribute('id'));

        $response->assertNoContent();

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->getAttribute('id'),
            'deleted_at' => null
        ])->assertDatabaseCount('vehicles', 1); // we have SoftDeletes, remember?
    }
}
